<?php
header('Content-Type: application/json; charset=UTF-8');

$errors = [
    'FK' => false,
    'DD' => false,
    'DF' => false,
];

$nir = trim($_POST['BEN_NIR_IDT'] ?? '');
$ald = trim($_POST['IMB_ALD_NUM'] ?? '');
$dateD = trim($_POST['IMB_ALD_DTD'] ?? '');
$dateF = trim($_POST['IMB_ALD_DTF'] ?? '');
$motE = trim($_POST['IMB_ETM_NAT'] ?? '');
$motMoP = trim($_POST['MED_MTF_COD'] ?? '');

$currentDate = strtotime(date('Y-m-d'));
$startDate = strtotime($dateD);
$endDate = $dateF === '' ? false : strtotime($dateF);

if ($startDate === false || $startDate >= $currentDate) {
    $errors['DD'] = true;
}

if ($dateF !== '' && ($endDate === false || $endDate < $startDate)) {
    $errors['DF'] = true;
}

if (
    $nir === '' ||
    $ald === '' ||
    $dateD === '' ||
    $motE === '' ||
    $motMoP === '' ||
    $errors['DD'] ||
    $errors['DF']
) {
    echo json_encode($errors);
    exit;
}

try {
    $db = new SQLite3('../../bdd/base.db');
    $db->enableExceptions(true);
    $db->exec('PRAGMA foreign_keys = ON');

    $stmt = $db->prepare(
        'INSERT INTO EB_IMB_R (BEN_NIR_IDT, IMB_ALD_NUM, IMB_ALD_DTD, IMB_ALD_DTF, IMB_ETM_NAT, MED_MTF_COD)
         VALUES (:nir, :ald, :dd, :df, :etm, :med)'
    );
    $stmt->bindValue(':nir', $nir, SQLITE3_TEXT);
    $stmt->bindValue(':ald', (int) $ald, SQLITE3_INTEGER);
    $stmt->bindValue(':dd', $dateD, SQLITE3_TEXT);
    $stmt->bindValue(':df', $dateF, SQLITE3_TEXT);
    $stmt->bindValue(':etm', (int) $motE, SQLITE3_INTEGER);
    $stmt->bindValue(':med', $motMoP, SQLITE3_TEXT);
    $stmt->execute();

    $db->close();
} catch (Throwable $e) {
    if (stripos($e->getMessage(), 'FOREIGN KEY') !== false) {
        $errors['FK'] = true;
    } else {
        // Keep error visible on the current UI contract.
        $errors['FK'] = true;
    }
}

echo json_encode($errors);

