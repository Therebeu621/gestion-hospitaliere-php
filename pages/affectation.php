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
    <title>Affectation</title>
    <?php //add styles
    include('includeFiles/styleFiles.html');
    include('includeFiles/bootstrapScripts.html');
    ?>
    <script type="text/javascript" src="../script/scriptAff.js"></script>
</head>

<body>

<?php //add navbar
include('includeFiles/navbar.php');
?>

<!-- Formulaire pour ajouter une affectation -->
<div class="formulaire addBen ">
    <h1>Ajouter une affection</h1>
    <form name="affection" id="formAddAffection">
        <label for="BEN_NIR_IDT" class="FK" id="0add"> BEN_NIR_IDT <span class="infobulle" aria-label="NIR anonymisé du bénéficiaire (19778802069526556)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="text"  name="BEN_NIR_IDT" required>
        <br>

        <label for="IMB_ALD_NUM" class="FK" id="1add"> IMB_ALD_NUM <span class="infobulle" aria-label="N° ALD (19)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="number"  name="IMB_ALD_NUM" required>
        <br>

        <label for="IMB_ALD_DTD" id="DD"> IMB_ALD_DTD <span class="infobulle" aria-label="Date de début ALD ou MP (2008-04-20)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="IMB_ALD_DTD" required>
        <br>


        <label for="IMB_ALD_DTF" id="DF"> IMB_ALD_DTF <span class="infobulle" aria-label="Date de fin ALD ou MP (2099-01-01)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="date" name="IMB_ALD_DTF">
        <br>

        <label for="IMB_ETM_NAT" id="4add"> IMB_ETM_NAT <span class="infobulle" aria-label="Motif exonération du bénéficiaire (41)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="number" name="IMB_ETM_NAT" required>
        <br>

        <label for="MED_MTF_COD" class="FK" id="5add"> MED_MTF_COD <span class="infobulle" aria-label="Motif médical ou pathologie-code CIM10 (D695)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
        <input type="text" name="MED_MTF_COD" required>
        <br>
        <br>

        <input type="submit" name="submit" value="Ajouter">
        <br>
        <br>

    </form>

</div>

<!-- tableau qui affiche les affectations -->
<div class="col formulaireList listElem">
    <h1>Toutes les affectations</h1>
    
    <?php
    # Pagination 
    $db = new SQLite3("../bdd/base.db");
    $currentPage = intval($_GET['page']) - 1 ;

    $res = $db->query("SELECT COUNT(*) as n FROM EB_IMB_R")->fetchArray();
    $max = intval($res['n'] / 10);
    if($currentPage > $max) header("Location:?page=$max");

    $i = 0;
    echo "<div class='numerotation'>";
    if($currentPage >= 1){
        echo "<a href='?page=".($currentPage)."'> <span> &#8592; </span> </a>";
    }

    if($currentPage+1 <= 0){
        header("Location:affectation.php?page=1");
    }

    while($i < 5 && $currentPage <= $max){
        echo "<a href='?page=".($currentPage+1)."'> ".($currentPage+1)." </a> <span style='color: white;'>-</span>";

        $i++;
        $currentPage++;
    }

    if($currentPage < $max){
        echo "<span style='color: white;'>...-</span>";
        echo "<a href='?page=".($max+1)."'>".($max+1)."</a>";
        echo "<a href='?page=".($currentPage-3)."'> <span>&#8594;</span> </a>";
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
            <h1>Modifier une affectation</h1>
            <form name="allUpdate" id="formBenUpdate">
                <label for="BEN_NIR_IDT"> BEN_NIR_IDT </label>
                <input type="text" name="BEN_NIR_IDT_UPDATE" id="BEN_NIR_IDT_UPDATE" readonly="true">

                <label for="IMB_ALD_NUM"> IMB_ALD_NUM </label>
                <input type="number" name="IMB_ALD_NUM_UPDATE" id="IMB_ALD_NUM_UPDATE">

                <label for="IMB_ALD_DTD"> IMB_ALD_DTD </label>
                <input type="date" name="IMB_ALD_DTD_UPDATE" id="IMB_ALD_DTD_UPDATE">

                <label for="IMB_ALD_DTF"> IMB_ALD_DTF </label>
                <input type="date" name="IMB_ALD_DTF_UPDATE" id="IMB_ALD_DTF_UPDATE">

                <label for="IMB_ETM_NAT"> IMB_ETM_NAT </label>
                <input type="number" name="IMB_ETM_NAT_UPDATE" id="IMB_ETM_NAT_UPDATE">

                <label for="MED_MTF_COD"> MED_MTF_COD </label>
                <input type="text" name="MED_MTF_COD_UPDATE" id="MED_MTF_COD_UPDATE" readonly="true">
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
