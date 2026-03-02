<?php
        $nir = trim(htmlspecialchars($_POST['BEN_NIR_IDT_UPDATE']), " ");
        $imbn = trim(htmlspecialchars($_POST['IMB_ALD_NUM_UPDATE']), " ");
        $imbdtd = trim(htmlspecialchars($_POST['IMB_ALD_DTD_UPDATE']), " ");
        $imbdtf = trim(htmlspecialchars($_POST['IMB_ALD_DTF_UPDATE']), " ");
        $imbnat = trim(htmlspecialchars($_POST['IMB_ETM_NAT_UPDATE']), " ");
        $med = trim(htmlspecialchars($_POST['MED_MTF_COD_UPDATE']), " ");
        $db = new PDO("sqlite:../../bdd/base.db");

        if(!empty($nir) && !empty($imbn) && !empty($imbdtd) && !empty($imbnat) && !empty($med)){
            if(intval($imbn) > 100) $imbn = "Erreur";
            if(!empty($imbdtf) &&  strtotime($imbdtd) > strtotime($imbdtf)){ $imbdtd = "0000-00-00"; $imbdtf = "0000-00-00";}
            if(empty($imbdtf) &&  strtotime($imbdtd) > time()) $imbdtd = "0000-00-00";;
            if(!empty($imbdtf) && strtotime($imbdtf) < strtotime($imbdtd)) $imbdtf = "0000-00-00";;
            if(intval($imbnat) > 100) $imbnat = "Erreur";
            if($imbn == "Erreur" && $imbdtf == "Erreur" && $imbdtd == "Erreur" && $imbnat == "Erreur"){
                $requete = "UPDATE EB_IMB_R
                SET IMB_ALD_NUM = '$imbn', IMB_ALD_DTD = '$imbdtd', IMB_ALD_DTF = '$imbdtf', IMB_ETM_NAT = '$imbnat'
                WHERE BEN_NIR_IDT = '$nir' AND MED_MTF_COD='$med'";
                $db->exec($requete);
            }
        }

        $response = array("$imbn","$imbdtd","$imbdtf","$imbnat","$med");
        echo json_encode($response);


?>
