<?php
$cle = trim(htmlspecialchars($_POST['CLE_TEC_JNT_UPDATE'])," ");
$nir = trim(htmlspecialchars($_POST['BEN_NIR_IDT_UPDATE'])," ");
$dD = trim(htmlspecialchars($_POST['EXE_SOI_DTD_UPDATE'])," ");
$dF = trim(htmlspecialchars($_POST['EXE_SOI_DTF_UPDATE'])," ");
$nPS = trim(htmlspecialchars($_POST['PFS_PRE_CRY_UPDATE'])," ");
$nPR = trim(htmlspecialchars($_POST['PRS_NAT_REF_UPDATE'])," ");
$dMDD = trim(htmlspecialchars($_POST['FLX_DIS_DTD_UPDATE'])," ");
$spe = trim(htmlspecialchars($_POST['PSE_ACT_SPE_UPDATE'])," ");
$cmu = trim(htmlspecialchars($_POST['BEN_CMU_TOP_UPDATE'])," ");
$dP = trim(htmlspecialchars($_POST['PRE_PRE_DTD_UPDATE'])," ");
$qua = trim(htmlspecialchars($_POST['PRS_ACT_QTE_UPDATE'])," ");


        if(!empty($dD) && !empty($dF) && !empty($nPS) && (!empty($nPR) || $cmu=="0") && !empty($dMDD) && (!empty($spe) || $cmu=="0") && (!empty($cmu) || $cmu=="0") && !empty($dP) && (!empty($qua) || $cmu=="0")) {
            if(strtotime($dD) > strtotime($dF) ) $dD = "Erreur";
            if(strtotime($dF) < strtotime($dD))  $dF = "Erreur";
            if(strlen($nPS) > 32 || strlen($nPS) < 25)  $nPS = "Erreur";
            if(intval($nPR) != 0 && (intval($nPR) < 1111))  $nPR = "Erreur";
            if(strlen($spe) > 8)  $spe = "Erreur";
            if(strlen($cmu) > 1)  $cmu = "Erreur";
            if(intval($qua) != 0 && intval($qua) != 1) $qua = "Erreur";
            if($dD != "Erreur" && $dF != "Erreur" && $nPS != "Erreur" && $nPR != "Erreur" && $dMDD != "Erreur" && $spe != "Erreur" && $cmu != "Erreur" && $dP != "Erreur" && $qua != "Erreur"){
                try{
                        $db = new PDO("sqlite:../../bdd/base.db");
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        $requete = "UPDATE ES_PRS_F
                        SET EXE_SOI_DTD = '$dD', EXE_SOI_DTF = '$dF', PFS_PRE_CRY = '$nPS', PRS_NAT_REF = '$nPR',
                        FLX_DIS_DTD = '$dMDD', PSE_ACT_SPE = '$spe', BEN_CMU_TOP = '$cmu', PRE_PRE_DTD = '$dP',
                        PRS_ACT_QTE = '$qua'
                        WHERE CLE_TEC_JNT = '$cle' AND BEN_NIR_IDT = '$nir'";
                        $db->exec($requete);
                }
                catch(Exception $e){
                        echo $e->getMessage();
                }
            }
            
        }

        $response = array("$cle","$nir","$dD","$dF","$nPS","$nPR","$dMDD","$spe","$cmu","$dP","$qua");
        echo json_encode($response);

        
?>
