<?php
$nir = htmlspecialchars($_POST['BEN_NIR_IDT']);
$ald = htmlspecialchars($_POST['IMB_ALD_NUM']);
$dateD = htmlspecialchars($_POST['IMB_ALD_DTD']);
$dateF = htmlspecialchars($_POST['IMB_ALD_DTF']);
$motE = htmlspecialchars($_POST['IMB_ETM_NAT']);
$motMoP = htmlspecialchars($_POST['MED_MTF_COD']);

$currentDate = mktime(0,0,0,date("m"),date("d"),date("Y"));

$db = new SQLite3("../../bdd/base.db");
$db->exec('PRAGMA foreign_keys = ON');
$tabError = array();
if(!empty($nir) && !empty($ald) && !empty($dateD) && !empty($motE) && !empty($motMoP)){
    $requete = "INSERT INTO EB_IMB_R VALUES (\"$nir\",\"$ald\",\"$dateD\",\"$dateF\",\"$motE\",\"$motMoP\")";
    $safe = $db->escapeString($requete);
    $db->exec($safe);
    $error = $db->lastErrorMsg();
    if($error == "not an error") echo json_encode(Array('FK' => false, 'DD' => false, 'DF' => false));
    else{
        if($error == "FOREIGN KEY constraint failed") $tabError['FK'] = true;
        else $tabError['FK'] = false;

        if(strtotime($dateD) >= $currentDate) $tabError['DD'] = true;
        else $tabError['DD'] = false;

        if(!empty($dateF) && strtotime($dateF) < strtotime($dateD)) $tabError['DF'] = true;
        else $tabError['DF'] = false;

        echo json_encode($tabError);
    }
}
?>
