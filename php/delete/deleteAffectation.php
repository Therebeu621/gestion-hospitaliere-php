<?php

    $ben = trim($_POST['ben'], " ");
    $med = trim($_POST['med'], " ");
    $db = new PDO("sqlite:../../bdd/base.db");

    $sql = $db->prepare('DELETE FROM EB_IMB_R WHERE BEN_NIR_IDT=:ben AND MED_MTF_COD =:med');
    $sql->bindParam(':ben',$ben,PDO::PARAM_STR);
    $sql->bindParam(':med',$med,PDO::PARAM_STR);
    $sql->execute();
    echo $ben;
    echo $med;
?>
