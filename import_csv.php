<?php



/*ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/includes/db.php";

set_time_limit(0);
ini_set('memory_limit', '512M');

$response = [
    'success' => false,
    'inserted' => 0,
    'ignored' => 0,
    'errors' => []
];

function normalize($name) {
    $name = trim($name);
    $name = strtolower($name);
    $name = str_replace(
        [' ', 'é', 'è', 'ê', 'à', 'ù', 'ï', 'ô', 'û', 'ç'],
        ['_', 'e', 'e', 'e', 'a', 'u', 'i', 'o', 'u', 'c'],
        $name
    );
    return $name;
}

function detectDelimiter($line) {
    $delimiters = [',', ';', "\t"];
    $counts = [];

    foreach ($delimiters as $d) {
        $counts[$d] = substr_count($line, $d);
    }

    return array_keys($counts, max($counts))[0];
}

// === UPLOAD CHECK ===
if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success"=>false,"errors"=>["Erreur upload"]]);
    exit;
}

$tmp = $_FILES['csv_file']['tmp_name'];
$handle = fopen($tmp, 'r');

if (!$handle) {
    echo json_encode(["success"=>false,"errors"=>["Impossible d'ouvrir le fichier"]]);
    exit;
}

// === DETECT DELIMITER ===
$firstLine = fgets($handle);
rewind($handle);

$delimiter = detectDelimiter($firstLine);
$enclosure = '"';

// === READ HEADER ===
$header = fgetcsv($handle, 0, $delimiter, $enclosure);

if ($header === false) {
    echo json_encode(["success"=>false,"errors"=>["Header invalide"]]);
    exit;
}

// nettoyage header (IMPORTANT)
$header = array_map(function($h) {
    $h = trim($h);
    $h = preg_replace('/^\xEF\xBB\xBF/', '', $h); // remove BOM
    return $h;
}, $header);

// === GET TABLE STRUCTURE ===
$tableName = 'valeurs_mensuelles';

$colsStmt = $pdo->prepare("
    SELECT COLUMN_NAME, EXTRA
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = :table
");
$colsStmt->execute(['table' => $tableName]);
$tableCols = $colsStmt->fetchAll(PDO::FETCH_ASSOC);

if (!$tableCols) {
    echo json_encode(["success"=>false,"errors"=>["Table introuvable"]]);
    exit;
}

// === BUILD COLUMN MAP ===
$normalizedCols = [];

foreach ($tableCols as $col) {
    $name = $col['COLUMN_NAME'];
    $norm = normalize($name);

    if (stripos($col['EXTRA'], 'auto_increment') !== false) continue;

    $normalizedCols[$norm] = $name;
}

// === BUILD HEADER MAP (clé = normalisée) ===
$headerMap = [];

foreach ($header as $h) {
    $norm = normalize($h);

    if ($norm === 'periode') continue;

    $headerMap[$norm] = $h;
}

// === DETECT NEW COLUMNS ===
$newCols = [];

foreach ($headerMap as $norm => $original) {
    if (!isset($normalizedCols[$norm])) {
        if (preg_match('/^\d{4}-\d{2}$/', $original)) {
            $newCols[] = $original;
        }
    }
}

// === ADD NEW COLUMNS ===
foreach ($newCols as $colName) {
    try {
        $sql = "ALTER TABLE `$tableName` ADD COLUMN `$colName` DECIMAL(10,1) DEFAULT NULL";
        $pdo->exec($sql);

        $normalizedCols[normalize($colName)] = $colName;

    } catch (Exception $e) {
        $response['errors'][] = "Erreur ajout colonne $colName : " . $e->getMessage();
    }
}

// === DETERMINE USABLE COLUMNS ===
$usableCols = [];

foreach ($normalizedCols as $norm => $realName) {
    if (isset($headerMap[$norm])) {
        $usableCols[] = $realName;
    }
}

if (count($usableCols) === 0) {
    echo json_encode([
        "success"=>false,
        "errors"=>["Aucune colonne commune trouvée"]
    ]);
    exit;
}

// === PREPARE INSERT ===
$placeholders = implode(',', array_fill(0, count($usableCols), '?'));
$columnsList = implode(',', array_map(fn($c)=>"`$c`", $usableCols));

$sqlInsert = "INSERT INTO `$tableName` ($columnsList) VALUES ($placeholders)";
$stmt = $pdo->prepare($sqlInsert);

// === INSERT DATA ===
$pdo->beginTransaction();

$lineNo = 1;
$inserted = 0;
$ignored = 0;

while (($row = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
    $lineNo++;
    
    // 🔍 DEBUG
    if ($lineNo === 2) { // première ligne de données
        echo "<pre>";
        echo "HEADER:\n";
        print_r($header);

        echo "\nROW:\n";
        print_r($row);

        echo "\nCOUNT HEADER: " . count($header);
        echo "\nCOUNT ROW: " . count($row);

        exit;
    }

    if (count($row) !== count($header)) {
        $ignored++;
        continue;
    }

    $rowAssoc = array_combine($header, $row);

    $values = [];

    foreach ($usableCols as $colName) {

        $norm = normalize($colName);

        if (!isset($headerMap[$norm])) {
            $values[] = null;
            continue;
        }

        $raw = $rowAssoc[$headerMap[$norm]] ?? null;

        if ($raw === '' || $raw === null) {
            $values[] = null;
            continue;
        }

        $clean = trim($raw);

        if (preg_match('/^[0-9]+([.,][0-9]+)?$/', $clean)) {
            $values[] = (float) str_replace(',', '.', $clean);
        } else {
            $values[] = $clean;
        }
    }

    try {
        $stmt->execute($values);
        $inserted++;
    } catch (Exception $e) {
        $response['errors'][] = "Ligne $lineNo: " . $e->getMessage();
        $ignored++;
    }
}

$pdo->commit();
fclose($handle);

$response['success'] = true;
$response['inserted'] = $inserted;
$response['ignored'] = $ignored;

echo json_encode($response);*/


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/includes/db.php";

set_time_limit(0);
ini_set('memory_limit', '512M');

$response = [
    'success' => false,
    'updated' => 0,
    'ignored' => 0,
    'errors' => []
];

function normalize($name) {
    $name = trim($name);
    $name = strtolower($name);
    $name = str_replace(
        [' ', 'é', 'è', 'ê', 'à', 'ù', 'ï', 'ô', 'û', 'ç'],
        ['_', 'e', 'e', 'e', 'a', 'u', 'i', 'o', 'u', 'c'],
        $name
    );
    return $name;
}

// === UPLOAD CHECK ===
if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success"=>false,"errors"=>["Erreur upload"]]);
    exit;
}

$tmp = $_FILES['csv_file']['tmp_name'];
$handle = fopen($tmp, 'r');

if (!$handle) {
    echo json_encode(["success"=>false,"errors"=>["Impossible d'ouvrir le fichier"]]);
    exit;
}

// ⚠️ TON FICHIER = TABULATION
$delimiter = "\t";
$enclosure = '"';

// === HEADER ===
$header = fgetcsv($handle, 0, $delimiter, $enclosure);

if ($header === false) {
    echo json_encode(["success"=>false,"errors"=>["Header invalide"]]);
    exit;
}

// nettoyage header
$header = array_map(function($h) {
    $h = trim($h);
    $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
    return $h;
}, $header);

// === STRUCTURE TABLE ===
$tableName = 'valeurs_mensuelles';

$colsStmt = $pdo->prepare("
    SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = :table
");
$colsStmt->execute(['table' => $tableName]);
$tableCols = $colsStmt->fetchAll(PDO::FETCH_COLUMN);

if (!$tableCols) {
    echo json_encode(["success"=>false,"errors"=>["Table introuvable"]]);
    exit;
}

// mapping colonnes DB normalisées
$dbColsNorm = [];
foreach ($tableCols as $col) {
    $dbColsNorm[normalize($col)] = $col;
}

// mapping header CSV
$headerMap = [];
foreach ($header as $h) {
    $norm = normalize($h);
    $headerMap[$norm] = $h;
}

// === AJOUT COLONNES MANQUANTES (YYYY-MM) ===
foreach ($header as $h) {
    $norm = normalize($h);

    if (!isset($dbColsNorm[$norm]) && preg_match('/^\d{4}-\d{2}$/', $h)) {
        try {
            $pdo->exec("ALTER TABLE `$tableName` ADD COLUMN `$h` DECIMAL(10,1) DEFAULT NULL");
            $dbColsNorm[$norm] = $h;
        } catch (Exception $e) {
            $response['errors'][] = "Erreur ajout colonne $h : " . $e->getMessage();
        }
    }
}

// === LOOP DATA ===
$pdo->beginTransaction();

$lineNo = 1;
$updated = 0;
$ignored = 0;

while (($row = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
    $lineNo++;

    if (count($row) !== count($header)) {
        $ignored++;
        continue;
    }

    //$rowAssoc = array_combine($header, $row);
    
    // 🔥 réalignement des données
    //$row = array_pad($row, count($header), null);
    
    // supprimer décalage à gauche (valeurs qui commencent trop tard)
    //while (count($row) > 0 && $row[4] === null) {
        //array_shift($row);
       // $row[] = null;
    //}
    
    //$rowAssoc = array_combine($header, $row);
    
    // trouver la première valeur numérique
    $startIndex = null;
    
    foreach ($row as $i => $val) {
        if (preg_match('/^[0-9]+([.,][0-9]+)?$/', trim($val))) {
            $startIndex = $i;
            break;
        }
    }
    
    // recalage
    if ($startIndex !== null && $startIndex > 4) {
        $shift = $startIndex - 4; // 4 = index de 2010-01
        $row = array_slice($row, $shift);
        $row = array_pad($row, count($header), null);
    }
    
    $rowAssoc = array_combine($header, $row);

    // 🔑 clé primaire métier
    $codeIndex = trim($rowAssoc['code_index'] ?? '');

    if ($codeIndex === '') {
        $ignored++;
        continue;
    }

    $setParts = [];
    $values = [];

    foreach ($dbColsNorm as $norm => $colName) {

        if ($colName === 'id' || $colName === 'code_index') continue;

        if (!isset($headerMap[$norm])) continue;

        $raw = $rowAssoc[$headerMap[$norm]] ?? null;

        // 🔥 NE PAS écraser avec NULL
        if ($raw === '' || $raw === null) continue;

        $clean = trim($raw);

        if (preg_match('/^[0-9]+([.,][0-9]+)?$/', $clean)) {
            $val = (float) str_replace(',', '.', $clean);
        } else {
            $val = $clean;
        }

        $setParts[] = "`$colName` = ?";
        $values[] = $val;
    }

    if (empty($setParts)) {
        $ignored++;
        continue;
    }

    $values[] = $codeIndex;

    $sql = "UPDATE `$tableName` SET " . implode(',', $setParts) . " WHERE `code_index` = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute($values);

        if ($stmt->rowCount() > 0) {
            $updated++;
        } else {
            $ignored++; // ligne non trouvée ou identique
        }

    } catch (Exception $e) {
        $response['errors'][] = "Ligne $lineNo: " . $e->getMessage();
        $ignored++;
    }
}

$pdo->commit();
fclose($handle);

$response['success'] = true;
$response['updated'] = $updated;
$response['ignored'] = $ignored;

echo json_encode($response);
