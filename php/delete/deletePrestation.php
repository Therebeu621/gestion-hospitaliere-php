<?php

    $ctj = trim($_POST['ctj'], " ");
    $db = new PDO("sqlite:../../bdd/base.db");

    $sql = $db->prepare('DELETE FROM ES_PRS_F WHERE CLE_TEC_JNT=:ctj');
    $sql->bindParam(':ctj',$ctj,PDO::PARAM_STR);
    $sql->execute();
?>
