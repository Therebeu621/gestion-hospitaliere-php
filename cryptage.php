<?php
function crypte($s){
    $v="";

    //ETAPE 1: INVERSER MAJ ET MIN
    for($i=0;$i<strlen($s);$i++){
        if(ord($s[$i])>=65 && ord($s[$i])<=90){ // si majuscule
            $v.=strtolower($s[$i]);
        }
        else if(ord($s[$i])>=97 && ord($s[$i])<=122) { // si majuscule
            $v.=strtoupper($s[$i]);
        }
        else $v.=$s[$i];
    }

    //ETAPE 2: INVERSE I ET I+1
    for($i=0;$i<strlen($s)-1;$i+=2){
        $tmp = $v[$i];
        $v[$i]=$v[$i+1];
        $v[$i+1]=$tmp;
    }

    //ETAPE 4: SI LEN DE $new >= 15 alors +1 sinon -1
    for($i=0;$i<strlen($v);$i++) {
        if (strlen($v) >= 15) $v[$i] = chr(ord($v[$i])+1);
        else $v[$i] = chr(ord($v[$i])-1);
    }

    return $v;
}


function decrypt($s){

    //ETAPE 1: SI LEN DE $new >= 15 alors -1 sinon +1
    for($i=0;$i<strlen($s);$i++) {
        if (strlen($s) >= 15) $s[$i] = chr(ord($s[$i])-1);
        else $s[$i] = chr(ord($s[$i])+1);
    }

    $v=$s;
    //ETAPE 3: INVERSE I ET I+1
    for($i=0;$i<strlen($s)-1;$i+=2){
        $tmp = $v[$i];
        $v[$i]=$v[$i+1];
        $v[$i+1]=$tmp;
    }

    //ETAPE 4: INVERSER MAJ ET MIN
    $new = "";
    for($i=0;$i<strlen($v);$i++){
        if(ord($v[$i])>=65 && ord($v[$i])<=90){ // si majuscule
            $new.=strtolower($v[$i]);
        }
        else if(ord($v[$i])>=97 && ord($v[$i])<=122) { // si majuscule
            $new.=strtoupper($v[$i]);
        }
        else $new.=$v[$i];
    }

    return $new;


}
?>
