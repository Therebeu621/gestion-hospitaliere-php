<?php
header('Content-Type: application/json; charset=UTF-8');

$errors = [
    'BEN' => false,
    'ANN' => false,
    'DPT' => false,
    'SEX' => false,
    'DCD' => false,
];

$nir = trim($_POST['BEN_NIR_IDT'] ?? '');
$ann = trim($_POST['BEN_NAI_ANN'] ?? '');
$dpt = trim($_POST['BEN_RES_DPT'] ?? '');
$sex = trim($_POST['BEN_SEX_COD'] ?? '');
$dcd = trim($_POST['BEN_DCD_AME'] ?? '');

$errors['BEN'] = !(strlen($nir) === 16 || strlen($nir) === 17);
$errors['ANN'] = !(strlen($ann) === 4 && ctype_digit($ann));
$errors['DPT'] = !(strlen($dpt) === 3);
$errors['SEX'] = !($sex === '1' || $sex === '2');

if ($dcd !== '') {
    if (!preg_match('/^\d{6}$/', $dcd)) {
        $errors['DCD'] = true;
    } else {
        $year = (int) substr($dcd, 0, 4);
        $month = (int) substr($dcd, 4, 2);
        $current = (int) date('Ym');
        $value = (int) $dcd;
        if ($month < 1 || $month > 12 || $year < 1900 || $value > $current) {
            $errors['DCD'] = true;
        }
    }
}

if (!in_array(true, $errors, true)) {
    try {
        $db = new SQLite3('../../bdd/base.db');
        $db->enableExceptions(true);
        $stmt = $db->prepare(
            'INSERT INTO EB_INB_F (BEN_NIR_IDT, BEN_NAI_ANN, BEN_RES_DPT, BEN_SEX_COD, BEN_DCD_AME)
             VALUES (:nir, :ann, :dpt, :sex, :dcd)'
        );
        $stmt->bindValue(':nir', $nir, SQLITE3_TEXT);
        $stmt->bindValue(':ann', $ann, SQLITE3_TEXT);
        $stmt->bindValue(':dpt', $dpt, SQLITE3_TEXT);
        $stmt->bindValue(':sex', (int) $sex, SQLITE3_INTEGER);
        $stmt->bindValue(':dcd', $dcd, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } catch (Throwable $e) {
        $message = $e->getMessage();
        if (stripos($message, 'UNIQUE') !== false || stripos($message, 'PRIMARY KEY') !== false) {
            $errors['BEN'] = true;
        } else {
            // Keep legacy front behavior: highlight all fields on unknown DB error.
            $errors['BEN'] = true;
            $errors['ANN'] = true;
            $errors['DPT'] = true;
            $errors['SEX'] = true;
            $errors['DCD'] = true;
        }
    }
}

echo json_encode($errors);

