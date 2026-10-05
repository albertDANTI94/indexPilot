<?php
// save_calcul.php

session_start();

require_once "../includes/db.php";
header("Content-Type: application/json");

file_put_contents(
    __DIR__ . '/debug_post.txt',
    print_r($_POST, true)
);

function generateCalculReference(PDO $pdo): string {

    $year = date('Y');

    $stmt = $pdo->prepare("
        SELECT reference
        FROM calculs
        WHERE reference LIKE :pattern
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->execute([
        'pattern' => "CAL-$year-%"
    ]);

    $last = $stmt->fetchColumn();

    if (!$last) {
        return "CAL-$year-0001";
    }

    $parts = explode('-', $last);
    $num = (int) end($parts);

    $next = str_pad($num + 1, 4, '0', STR_PAD_LEFT);

    return "CAL-$year-$next";
}

try {

    // -----------------------------
    // R�CUP�RATION POST
    // -----------------------------
    $tarif     = floatval($_POST["Tarif"] ?? 0);
    $indice0   = floatval($_POST["ICHT-N0"] ?? 0);
    $indiceN   = floatval($_POST["ICHT-Nn"] ?? 0);
    $partFerme = floatval($_POST["part_ferme"] ?? 0);

    $libelle   = $_POST["libelle"] ?? null;
    $date0 = $_POST["date0"] ?? null;
    $dateN = $_POST["dateN"] ?? null;
    
    $date0 = !empty($_POST["date0"]) ? $_POST["date0"] . "-01" : null;
    $dateN = !empty($_POST["dateN"]) ? $_POST["dateN"] . "-01" : null;
    
    //$date0 = preg_match('/^\d{4}-\d{2}$/', $date0) ? $date0 : null;
    //$dateN = preg_match('/^\d{4}-\d{2}$/', $dateN) ? $dateN : null;

    

    // -----------------------------
    // VALIDATIONS
    // -----------------------------
    if ($tarif <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Le tarif doit être supérieur à 0."
        ]);
        exit;
    }

    if ($indice0 <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Indice 0 invalide."
        ]);
        exit;
    }

    if ($indiceN <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Indice N invalide."
        ]);
        exit;
    }

    if ($partFerme < 0 || $partFerme > 100) {
        echo json_encode([
            "success" => false,
            "message" => "La part ferme doit être entre 0 et 100."
        ]);
        exit;
    }
    
    /*if (!preg_match('/^\d{4}-\d{2}$/', $date0)) {
    $date0 = null;
    }
    
    if (!preg_match('/^\d{4}-\d{2}$/', $dateN)) {
        $dateN = null;
    }*/

    // -----------------------------
    // CALCUL
    // -----------------------------
    $ratio = $indiceN / $indice0;
    $ratio = ceil($ratio * 1000) / 1000;
    $ratio = round($ratio, 3);


    if (!is_finite($ratio)) {
        echo json_encode([
            "success" => false,
            "message" => "Erreur de calcul (ratio invalide)"
        ]);
        exit;
    }

    $coefFixe     = $partFerme / 100;
    $coefVariable = (100 - $partFerme) / 100;

    $coefficient_brut = $ratio;

    if (!is_finite($coefficient_brut)) {
        echo json_encode([
            "success" => false,
            "message" => "Coefficient invalide"
        ]);
        exit;
    }

    // -----------------------------
    // ARRONDI M�TIER (comme get_lot.php)
    // -----------------------------
    $coefficient = ceil($coefficient_brut * 1000) / 1000;
    $coefficient = round($coefficient, 3);

    //$nouveauTarif = $tarif * $coefficient;
    $nouveauTarif = $tarif * ($coefFixe + ($coefVariable * $ratio));
    $nouveauTarif = round($nouveauTarif, 2);
    
    // -----------------------------
    // RECUPERATION NOM DE CALCUL AUTO
    //------------------------------
    $reference = generateCalculReference($pdo);

    // -----------------------------
    // INSERT BDD
    // -----------------------------
    $stmt = $pdo->prepare("
    INSERT INTO calculs 
        (reference, tarif_origin, indice_n0, indice_nn, coefficient, nouveau_tarif, libelle, date0, dateN, part_ferme)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $reference,
        $tarif,
        $indice0,
        $indiceN,
        $coefficient,
        $nouveauTarif,
        $libelle,
        $date0,
        $dateN,
        $partFerme
    ]);

    // -----------------------------
    // RESPONSE JSON
    // -----------------------------
    echo json_encode([
        "success"       => true,
        "Cn"            => $coefficient,
        "nouveauTarif"  => $nouveauTarif
    ]);

    exit;

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);

    exit;
}

