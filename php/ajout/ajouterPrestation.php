<?php
    $nir = htmlspecialchars($_POST['BEN_NIR_IDT']);
    $dD = htmlspecialchars($_POST['EXE_SOI_DTD']);
    $dF = htmlspecialchars($_POST['EXE_SOI_DTF']);
    $nPS = htmlspecialchars($_POST['PFS_PRE_CRY']);
    $nPR = htmlspecialchars($_POST['PRS_NAT_REF']);
    $dMDD = htmlspecialchars($_POST['FLX_DIS_DTD']);
    $spe = htmlspecialchars($_POST['PSE_ACT_SPE']);
    $cmu = htmlspecialchars($_POST['BEN_CMU_TOP']);
    $dP = htmlspecialchars($_POST['PRE_PRE_DTD']);
    $qua = htmlspecialchars($_POST['PRS_ACT_QTE']);
    $db = new SQLite3("../../bdd/base.db");
    $tabError = array();
    $res = "";
        
    $i = 0;
    while($i < 77){
        $r = rand(1, 9);
        $res .= $r;
        $i++;
    }
    if(!empty($nir) && !empty($dD) && !empty($dF) && !empty($nPS) && !empty($nPR) && !empty($dMDD) && !empty($spe) && (!empty($cmu) || $cmu == "0") && !empty($dP) && !empty($qua)) {
        $requete = "INSERT INTO ES_PRS_F VALUES (\"$res\",\"$nir\",\"$dD\",\"$dF\",\"$nPS\",\"$nPR\",\"$dMDD\",\"$spe\",\"$cmu\",\"$dP\",\"$qua\")";
        if(strtotime($dD) <= time() && strtotime($dF) >= strtotime($dD)){
            $safe = $db->escapeString($requete); 
            $db->exec($safe);
            $tabError['nir'] = $error != "FOREIGN KEY constraint failed" || strlen($nir);
            $tabError['dD'] = strtotime($dD) < time();
            $tabError['dF'] = strtotime($dF) >= strtotime($dD);
            $tabError['nPS'] = (strlen($nPS) <= 32 && strlen($nPS) >= 25);
            $tabError['nPR'] = $error != "FOREIGN KEY constraint failed";
            $tabError['spe'] = strlen($spe) <= 8;
            $tabError['cmu'] = strlen($cmu) == 1 ;
            $tabError['qua'] = intval($qua) == 0 || intval($qua) == 1;

            echo json_encode($tabError);
        }
        else{
            $tabError['dD'] = strtotime($dD) <= time();
            $tabError['dF'] = strtotime($dF) > strtotime($dD);

            echo json_encode($tabError);
        } 
    }
?>
