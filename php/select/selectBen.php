<?php
    $off = $_POST['off'];
    $db = new SQLite3('../../bdd/base.db');
    $infos = $db->query("SELECT * FROM EB_INB_F LIMIT 20 OFFSET $off");
    $tab = array();
    while($row = $infos->fetchArray(SQLITE3_ASSOC)){
        $tab[] = $row;
    }

    echo json_encode($tab);

?>
