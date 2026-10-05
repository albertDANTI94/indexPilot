<?php
session_start();

require_once "../includes/db.php";
header("Content-Type: application/json");

$user_id = $_SESSION['userId'] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Utilisateur non connecté"
    ]);
    exit;
}

// ---------------- VALIDATION ----------------
$required = ["id", "chantier_id", "nom", "tarif_origine", "part_ferme", "libelle", "date0", "dateN", "indice0", "indiceN", "status"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === "") {
        echo json_encode([
            "success" => false,
            "message" => "Champ manquant : $field"
        ]);
        exit;
    }
}

// ---------------- SANITIZE ----------------
$id            = (int) $_POST["id"];
$chantier_id   = (int) $_POST["chantier_id"];
$nom           = trim($_POST["nom"]);
$tarif_origine = (float) $_POST["tarif_origine"];
$partFerme     = (float) $_POST["part_ferme"];
$libelle       = trim($_POST["libelle"]);
$status        = trim($_POST["status"]);

$date0_raw = trim($_POST["date0"]);
$dateN_raw = trim($_POST["dateN"]);

$date0 = preg_match('/^\d{4}-\d{2}$/', $date0_raw) ? $date0_raw . '-01' : null;
$dateN = preg_match('/^\d{4}-\d{2}$/', $dateN_raw) ? $dateN_raw . '-01' : null;

$indice0 = (float) $_POST["indice0"];
$indiceN = (float) $_POST["indiceN"];

// ---------------- VALIDATIONS ----------------
if ($tarif_origine <= 0) {
    echo json_encode(["success" => false, "message" => "Tarif invalide"]);
    exit;
}

if ($indice0 <= 0) {
    echo json_encode(["success" => false, "message" => "Indice 0 invalide"]);
    exit;
}

if ($indiceN <= 0) {
    echo json_encode(["success" => false, "message" => "Indice N invalide"]);
    exit;
}

if ($partFerme < 0 || $partFerme > 100) {
    echo json_encode(["success" => false, "message" => "Part ferme invalide"]);
    exit;
}

// ---------------- CALCUL ----------------
$ratio = $indiceN / $indice0;
$ratio = round(ceil($ratio * 1000) / 1000, 3);

$coefFixe     = $partFerme / 100;
$coefVariable = (100 - $partFerme) / 100;

$coefficient = round(ceil($ratio * 1000) / 1000, 3);

$nouveauTarif = round(
    $tarif_origine * ($coefFixe + ($coefVariable * $ratio)),
    2
);

// ---------------- UPDATE ----------------
try {
    $stmt = $pdo->prepare("
        UPDATE lots
        SET
            chantier_id = ?,
            nom = ?,
            tarif_origine = ?,
            date0 = ?,
            dateN = ?,
            libelle = ?,
            indice0 = ?,
            indiceN = ?,
            coefficient = ?,
            nouveau_tarif = ?,
            part_ferme = ?,
            status = ?,
            updated_at = NOW()
        WHERE id = ? AND user_id = ?
    ");

    $stmt->execute([
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
        $status,
        $id,
        $user_id
    ]);

    echo json_encode([
        "success" => true,
        "Cn" => $coefficient,
        "nouveauTarif" => $nouveauTarif
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur SQL : " . $e->getMessage()
    ]);
}