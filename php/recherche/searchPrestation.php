<?php

    $id = trim(htmlspecialchars($_POST['id']), ' ');
    $db = new PDO("sqlite:../../bdd/base.db");
    $result = $db->query("SELECT * FROM ES_PRS_F WHERE CLE_TEC_JNT LIKE '$id%' LIMIT 15");
    $tab = array();
    while(($row = $result->fetch(PDO::FETCH_ASSOC))){
        $tab[] = $row;
    }

    echo json_encode($tab);

?>
