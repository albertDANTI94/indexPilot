<?php
session_start();

$user_id = $_SESSION['userId'];

require_once "../includes/db.php";
header("Content-Type: application/json");

// Vérification des champs obligatoires
$required = ["chantier_id", "nom", "tarif_origine", "part_ferme", "libelle", "date0", "dateN", "indice0", "indiceN", "status"];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === "") {
        echo json_encode([
            "success" => false,
            "message" => "Champ manquant : $field"
        ]);
        exit;
    }
}

$chantier_id   = intval($_POST["chantier_id"]);
$nom           = trim($_POST["nom"]);
$tarif_origine = floatval($_POST["tarif_origine"]);
$partFerme     = floatval($_POST["part_ferme"]);
$libelle     = trim($_POST["libelle"]);
$date0       = trim($_POST["date0"]);
$dateN       = trim($_POST["dateN"]);
$indice0       = floatval($_POST["indice0"]);
$indiceN       = floatval($_POST["indiceN"]);
$status        = trim($_POST["status"]);


// Calculs
if ($indice0 == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Indice 0 ne peut pas être 0."
    ]);
    exit;
}

if ($tarif_origine <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Le tarif doit être supérieur à 0."
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
    // ARRONDI MÉTIER (comme get_lot.php)
    // -----------------------------
    $coefficient = ceil($coefficient_brut * 1000) / 1000;
    $coefficient = round($coefficient, 3);

    //$nouveauTarif = $tarif * $coefficient;
    $nouveauTarif = $tarif_origine * ($coefFixe + ($coefVariable * $ratio));
    $nouveauTarif = round($nouveauTarif, 2);

    $date0 = $_POST['date0'] . '-01';
    $dateN = $_POST['dateN'] . '-01';

try {
    $stmt = $pdo->prepare("
        INSERT INTO lots (user_id, chantier_id, nom, tarif_origine, date0, dateN, libelle, indice0, indiceN, coefficient, nouveau_tarif, part_ferme, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $chantier_id,
        $nom,
        $tarif_origine,
        $date0,
        $dateN,
        $libelle,
        $indice0,
        $indiceN,
        $coefficient,
        $nouveauTarif,
        $partFerme,
        $status
    ]);

    echo json_encode([
        //"success"      => true,
        "message"      => "Lot créé",
        //"coefficient" => number_format($coefficient, 3, '.', ''),
        //"nouveau_tarif"=> round($nouveau_tarif, 2)
        "success"       => true,
        "Cn"            => $coefficient,
        "nouveauTarif"  => $nouveauTarif
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur SQL : " . $e->getMessage()
    ]);
    exit;
}

