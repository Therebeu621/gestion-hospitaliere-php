<?php
    $off = $_POST['off'];
    $db = new SQLite3('../../bdd/base.db');
    $infos = $db->query("SELECT * FROM ES_PRS_F LIMIT 10 OFFSET $off");
    $tab = array();
    $i = 0;
    while($row = $infos->fetchArray(SQLITE3_ASSOC)){
        $tab[] = $row;
    }

    echo json_encode($tab);

?>
