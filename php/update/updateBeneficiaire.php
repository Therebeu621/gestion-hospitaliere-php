<?php
        $nir = trim(htmlspecialchars($_POST['BEN_NIR_IDT_UPDATE']), " ");
        $ann = trim(htmlspecialchars($_POST['BEN_NAI_ANN_UPDATE']), " ");
        $dpt = trim(htmlspecialchars($_POST['BEN_RES_DPT_UPDATE']), " ");
        $sex = trim(htmlspecialchars($_POST['BEN_SEX_COD_UPDATE']), " ");
        $dcd = trim(htmlspecialchars($_POST['BEN_DCD_AME_UPDATE']), " ");
        $db = new PDO("sqlite:../../bdd/base.db");
        if(!empty($nir) && !empty($ann) && !empty($dpt) && !empty($sex)){
            if(strlen($ann) != 4) $ann = "Erreur";
            if((strlen($ann) == 4 && !empty($dcd) && (strtotime($ann.date("m").date("d")) > strtotime($dcd.date("d"))))){
                $ann = "Erreur";
                $dcd = "Erreur";
            }
            if(strlen($ann) == 4 && empty($dcd) && strtotime($ann.date("m").date("d")) < strtotime(($dcd.date("d")))){
                $ann = "Erreur";
            }
            if(strlen($dpt) != 3){
                $dpt = "Erreur";
            }
            if($sex != 1 && $sex != 2){
                $sex = "Erreur";
            }
            if(!empty($dcd) && (strlen($dcd) != 6 && strtotime($dcd.date("d")) > time())){
                $dcd = "Erreur";
            }
            
            if($ann != "Erreur" && $dpt != "Erreur" && $sex != "Erreur" && $dcd != "Erreur"){
                $requete = "UPDATE EB_INB_F
                SET BEN_NAI_ANN = '$ann', BEN_RES_DPT = '$dpt', BEN_SEX_COD = '$sex', BEN_DCD_AME = '$dcd'
                WHERE BEN_NIR_IDT = '$nir'";
                $db->exec($requete);
            }
            $response = array("$ann","$dpt","$sex","$dcd");
            echo json_encode($response);
        }
?>