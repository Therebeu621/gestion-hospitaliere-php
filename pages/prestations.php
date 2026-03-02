<?php
session_start();
if(!isset($_SESSION['login'])) {
    include('includeFiles/styleFiles.html');
    echo "<div class='htbConnected'><h1>You have to be login!</h1></div>";
    header( "refresh:2;url=../index.php" );
}
else {
?><!doctype html>
<html lang='fr'>
<head>
    <meta charset='utf - 8'>
    <title>Prestation</title>
    <?php //add styles
    include('includeFiles/styleFiles.html');
    include('includeFiles/bootstrapScripts.html');
    ?>
    <script type="text/javascript" src="../script/scriptPres.js">  </script>

</head>

<body>

<?php //add navbar
include('includeFiles/navbar.php');
?>


<div class="formulairePres addPres ">
    <h1>Ajouter une prestation</h1>
    <form name="prestation" id="formAddPrestation">
        <label for="BEN_NIR_IDT" id="nir"> BEN_NIR_IDT <span class="infobulle" aria-label="NIR anonymisé du bénéficiaire (19778802069526556)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="text" name="BEN_NIR_IDT" required>

        <label for="EXE_SOI_DTD" id="dD"> EXE_SOI_DTD <span class="infobulle" aria-label="Date de début d'exécution des soins (jj-mm-yyyy)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="EXE_SOI_DTD" required>

        <label for="EXE_SOI_DTF" id="dF"> EXE_SOI_DTF <span class="infobulle" aria-label="Date de fin d'exécution des soins (2099-01-01)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="EXE_SOI_DTF">

        <br>


        <label for="PFS_PRE_CRY" id="nPS"> PFS_PRE_CRY <span class="infobulle" aria-label="N° PS prescripteur crypté (32char)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="text" name="PFS_PRE_CRY" required>

        <label for="PRS_NAT_REF" id="nPR"> PRS_NAT_REF <span class="infobulle" aria-label="Nature de la prestation de référence"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="number" name="PRS_NAT_REF" required>

        <label for="FLX_DIS_DTD" id="dMDD"> FLX_DIS_DTD <span class="infobulle" aria-label="Date de mise à disposition des données (1er jour du mois qui suit la date de traitement)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="FLX_DIS_DTD" required>

        <br>


        <label for="PSE_ACT_SPE" id="spe"> PSE_ACT_SPE <span class="infobulle" aria-label="Spécialité ou nature d'activité du PS exécutant"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="number" name="PSE_ACT_SPE" required>

        <label for="BEN_CMU_TOP" id="cmu"> BEN_CMU_TOP <span class="infobulle" aria-label="Top bénéficiaire CMU Complémentaire"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="text" name="BEN_CMU_TOP" required>

        <br>
        <label for="PRE_PRE_DTD" id="dP"> PRE_PRE_DTD <span class="infobulle" aria-label="Date de prescription"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="PRE_PRE_DTD" required>

        <label for="PRS_ACT_QTE" id="qua"> PRS_ACT_QTE <span class="infobulle" aria-label="Quantité (signée) de l'acte de base"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="number" name="PRS_ACT_QTE">
        <br>


        <br>

        <input type="submit" name="submit" value="Ajouter">
        <br>
        <br>

    </form>

</div>

<div class="col formulaireListPresta listElemPresta">
    <h1>Toutes les prestations</h1>

    <?php

    $db = new SQLite3("../bdd/base.db");
    $currentPage = intval($_GET['page']) - 1 ;
    $res = $db->query("SELECT COUNT(*) as n FROM ES_PRS_F")->fetchArray();
    $max = intval($res['n'] / 10);
    if($currentPage > $max) header("Location:?page=$max");
    $i = 0;
    echo "<div class='numerotation'>";
    if($currentPage >= 1){
        echo "<a href='?page=".($currentPage)."'> <span> &#8592; </span> </a>";
    }

    if($currentPage+1 <= 0){
        header("Location:prestations.php?page=1");
    }

    while($i < 5 && $currentPage <= $max){
        echo "<a href='?page=".($currentPage+1)."'> ".($currentPage+1)." </a> <span style='color: white;'>-</span>";

        $i++;
        $currentPage++;
    }

    if($currentPage < $max){
        echo "<span style='color: white;'>...-</span>";
        echo "<a href='?page=".($max+1)."'>".($max+1)."</a>";
        echo "<a href='?page=".($currentPage)."'> <span>&#8594;</span> </a>";
    }

    echo "</div>";

    ?>
</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="allUpdate">
            <h1>Modifier une prestation</h1>
            <form name="allUpdate" id="formPreUpdate">
                <label for="CLE_TEC_JNT"> CLE_TEC_JNT </label>
                <input type="text" name="CLE_TEC_JNT_UPDATE" id="CLE_TEC_JNT_UPDATE" readonly="true">

                <label for="BEN_NIR_IDT"> BEN_NIR_IDT </label>
                <input type="text" name="BEN_NIR_IDT_UPDATE" id="BEN_NIR_IDT_UPDATE" readonly="true">

                <label for="EXE_SOI_DTD"> EXE_SOI_DTD </label>
                <input type="date" name="EXE_SOI_DTD_UPDATE" id="EXE_SOI_DTD_UPDATE">

                <label for="EXE_SOI_DTF"> EXE_SOI_DTF </label>
                <input type="date" name="EXE_SOI_DTF_UPDATE" id="EXE_SOI_DTF_UPDATE">

                <label for="PFS_PRE_CRY"> PFS_PRE_CRY </label>
                <input type="text" name="PFS_PRE_CRY_UPDATE" id="PFS_PRE_CRY_UPDATE">

                <label for="PRS_NAT_REF"> PRS_NAT_REF </label>
                <input type="number" name="PRS_NAT_REF_UPDATE" id="PRS_NAT_REF_UPDATE">
                <br>

                <label for="FLX_DIS_DTD"> FLX_DIS_DTD </label>
                <input type="date" name="FLX_DIS_DTD_UPDATE" id="FLX_DIS_DTD_UPDATE"> 
                <br>

                <label for="PSE_ACT_SPE"> PSE_ACT_SPE </label>
                <input type="number" name="PSE_ACT_SPE_UPDATE" id="PSE_ACT_SPE_UPDATE">
                <br>

                <label for="BEN_CMU_TOP"> BEN_CMU_TOP </label>
                <input type="text" name="BEN_CMU_TOP_UPDATE" id="BEN_CMU_TOP_UPDATE">
                <br>

                <label for="PRE_PRE_DTD"> PRE_PRE_DTD </label>
                <input type="date" name="PRE_PRE_DTD_UPDATE" id="PRE_PRE_DTD_UPDATE">
                <br>

                <label for="PRS_ACT_QTE"> PRS_ACT_QTE </label>
                <input type="number" name="PRS_ACT_QTE_UPDATE" id="PRS_ACT_QTE_UPDATE">
                <br>


                <br>

                <input type="submit" name="submit" value="Ajouter">
                <br>
                <br>

            </form>
        </div>
    </div>

</div>

</body>
</html>
<?php
}
?>
