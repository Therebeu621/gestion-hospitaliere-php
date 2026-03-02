<?php

    $id = trim(htmlspecialchars($_POST['id']), ' ');
    $db = new PDO("sqlite:../../bdd/base.db");

    $result = $db->query("SELECT * FROM EB_IMB_R WHERE BEN_NIR_IDT LIKE '$id%' LIMIT 25");
    $tab = array();
    while(($row = $result->fetch(PDO::FETCH_ASSOC))){
        $tab[] = $row;
    }

    echo json_encode($tab);

?>
