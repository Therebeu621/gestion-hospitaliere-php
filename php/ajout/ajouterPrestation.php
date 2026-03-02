<?php
header('Content-Type: application/json; charset=UTF-8');

$result = [
    'nir' => true,
    'dD' => true,
    'dF' => true,
    'nPS' => true,
    'nPR' => true,
    'dMDD' => true,
    'spe' => true,
    'cmu' => true,
    'dP' => true,
    'qua' => true,
];

$nir = trim($_POST['BEN_NIR_IDT'] ?? '');
$dD = trim($_POST['EXE_SOI_DTD'] ?? '');
$dF = trim($_POST['EXE_SOI_DTF'] ?? '');
$nPS = trim($_POST['PFS_PRE_CRY'] ?? '');
$nPR = trim($_POST['PRS_NAT_REF'] ?? '');
$dMDD = trim($_POST['FLX_DIS_DTD'] ?? '');
$spe = trim($_POST['PSE_ACT_SPE'] ?? '');
$cmu = trim($_POST['BEN_CMU_TOP'] ?? '');
$dP = trim($_POST['PRE_PRE_DTD'] ?? '');
$qua = trim($_POST['PRS_ACT_QTE'] ?? '');

$today = strtotime(date('Y-m-d'));
$start = strtotime($dD);
$end = strtotime($dF);

$result['nir'] = (strlen($nir) === 16 || strlen($nir) === 17);
$result['dD'] = $start !== false && $start <= $today;
$result['dF'] = $end !== false && $start !== false && $end >= $start;
$result['nPS'] = strlen($nPS) >= 25 && strlen($nPS) <= 32;
$result['nPR'] = ctype_digit($nPR);
$result['dMDD'] = strtotime($dMDD) !== false;
$result['spe'] = ctype_digit($spe) && strlen($spe) <= 8;
$result['cmu'] = strlen($cmu) === 1;
$result['dP'] = strtotime($dP) !== false;
$result['qua'] = ($qua === '0' || $qua === '1');

if (!in_array(false, $result, true)) {
    try {
        $db = new SQLite3('../../bdd/base.db');
        $db->enableExceptions(true);
        $db->exec('PRAGMA foreign_keys = ON');

        $checkBen = $db->prepare('SELECT 1 FROM EB_INB_F WHERE BEN_NIR_IDT = :nir LIMIT 1');
        $checkBen->bindValue(':nir', $nir, SQLITE3_TEXT);
        $hasBen = $checkBen->execute()->fetchArray(SQLITE3_NUM) !== false;
        if (!$hasBen) {
            $result['nir'] = false;
        }

        $checkNat = $db->prepare('SELECT 1 FROM IR_NAT_V WHERE PRS_NAT = :nat LIMIT 1');
        $checkNat->bindValue(':nat', (int) $nPR, SQLITE3_INTEGER);
        $hasNat = $checkNat->execute()->fetchArray(SQLITE3_NUM) !== false;
        if (!$hasNat) {
            $result['nPR'] = false;
        }

        if (!in_array(false, $result, true)) {
            $inserted = false;
            for ($attempt = 0; $attempt < 5 && !$inserted; $attempt++) {
                $ctj = '';
                for ($i = 0; $i < 77; $i++) {
                    $ctj .= (string) random_int(1, 9);
                }

                $stmt = $db->prepare(
                    'INSERT INTO ES_PRS_F (
                        CLE_TEC_JNT, BEN_NIR_IDT, EXE_SOI_DTD, EXE_SOI_DTF, PFS_PRE_CRY, PRS_NAT_REF,
                        FLX_DIS_DTD, PSE_ACT_SPE, BEN_CMU_TOP, PRE_PRE_DTD, PRS_ACT_QTE
                    ) VALUES (
                        :ctj, :nir, :dd, :df, :nps, :npr, :dmdd, :spe, :cmu, :dp, :qua
                    )'
                );
                $stmt->bindValue(':ctj', $ctj, SQLITE3_TEXT);
                $stmt->bindValue(':nir', $nir, SQLITE3_TEXT);
                $stmt->bindValue(':dd', $dD, SQLITE3_TEXT);
                $stmt->bindValue(':df', $dF, SQLITE3_TEXT);
                $stmt->bindValue(':nps', $nPS, SQLITE3_TEXT);
                $stmt->bindValue(':npr', (int) $nPR, SQLITE3_INTEGER);
                $stmt->bindValue(':dmdd', $dMDD, SQLITE3_TEXT);
                $stmt->bindValue(':spe', (int) $spe, SQLITE3_INTEGER);
                $stmt->bindValue(':cmu', $cmu, SQLITE3_TEXT);
                $stmt->bindValue(':dp', $dP, SQLITE3_TEXT);
                $stmt->bindValue(':qua', (int) $qua, SQLITE3_INTEGER);

                try {
                    $stmt->execute();
                    $inserted = true;
                } catch (Throwable $e) {
                    if (stripos($e->getMessage(), 'UNIQUE constraint failed: ES_PRS_F.CLE_TEC_JNT') === false) {
                        throw $e;
                    }
                }
            }

            if (!$inserted) {
                $result['nir'] = false;
            }
        }

        $db->close();
    } catch (Throwable $e) {
        // Front marks fields in red when value is false.
        foreach ($result as $key => $ok) {
            $result[$key] = false;
        }
    }
}

echo json_encode($result);

