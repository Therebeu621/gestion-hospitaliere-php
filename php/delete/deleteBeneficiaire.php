<?php

    $ben = htmlspecialchars($_POST['ben']);
    try{
    
    $db = new PDO("sqlite:../../bdd/base.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->exec("DELETE FROM EB_INB_F WHERE BEN_NIR_IDT=$ben");
    echo "success !";
    }
    catch(Exception $e){
    
    echo $e->getMessage();
    }
?>
