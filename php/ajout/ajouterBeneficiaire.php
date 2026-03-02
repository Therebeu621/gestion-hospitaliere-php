<?php
    $nir = htmlspecialchars($_POST['BEN_NIR_IDT']);
    $ann = htmlspecialchars($_POST['BEN_NAI_ANN']);
    $dpt = htmlspecialchars($_POST['BEN_RES_DPT']);
    $sex = htmlspecialchars($_POST['BEN_SEX_COD']);
    $dcd = htmlspecialchars($_POST['BEN_DCD_AME']);
    $db = new SQLite3("../../bdd/base.db");
    if(!empty($nir) && !empty($ann) && !empty($dpt) && !empty($sex)){
        $requete = "INSERT INTO EB_INB_F VALUES (\"$nir\",\"$ann\",\"$dpt\",\"$sex\",\"$dcd\")";
        $safe = $db->escapeString($requete);
        $db->exec($safe);
        $tabError = array();
        if($db->lastErrorMsg() == "not an error") echo "Bénéficiaire créé";
        else{
            $err = $db->lastErrorMsg();
            $tabError['BEN'] = (strlen($nir) != 16 && strlen($nir) != 17 || str_contains("$err","UNIQUE"));
            $tabError['ANN'] = (strlen($ann) < 4 || strlen($ann) > 4);
            $tabError['DPT'] = (strlen($dpt) != 3);
            $tabError['SEX'] = ($sex != 1 && $sex != 2);
            if((strlen($dcd) == 6 && is_numeric($dcd)) || empty($dcd)){
                $mois = $dcd[4].$dcd[5];
                $annee = $dcd[0].$dcd[1].$dcd[2].$dcd[3];
                if(strtotime($dcd.date("d")) > time() && !empty($dcd)) $tabError['DCD'] = true;
                else $tabError['DCD'] = false;
            }
            else{
                $tabError['DCD'] = true;
            }
            echo json_encode($tabError);
        }
    }
?>