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
      <title>Beneficiaire</title>
        <?php //add styles
        include('includeFiles/bootstrapScripts.html');
        include('includeFiles/styleFiles.html');
        ?>
        <script type="text/javascript" src="../script/scriptBen.js">  </script>
    </head>

    <body>

    <?php //add navbar
    include('includeFiles/navbar.php');
    ?>


        <div class="formulaire addBen ">
            <h1>Ajouter un bénéficiaire</h1>
            <form name="beneficiaire" id="formBen">
                <label for="BEN_NIR_IDT" id="BEN"> BEN_NIR_IDT <span class="infobulle" aria-label="NIR anonymisé du bénéficiaire (19778802069526556)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
                <input type="text" name="BEN_NIR_IDT" required>

                <label for="BEN_NAI_ANN" id="ANN"> BEN_NAI_ANN <span class="infobulle" aria-label="Année de naissance du bénéficiaire (1920)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
                <input type="text" name="BEN_NAI_ANN"  required>

                <label for="BEN_RES_DPT" id="DPT"> BEN_RES_DPT <span class="infobulle" aria-label="Département de résidence du bénéficiaire (062)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
                <input type="text" name="BEN_RES_DPT"  required>

                <label for="BEN_SEX_COD" id="SEX"> BEN_SEX_COD <span class="infobulle" aria-label="Sexe du bénéficiaire donné en chiffre (1:femme | 2:homme)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
                <input type="text" name="BEN_SEX_COD"  required>

                <label for="BEN_DCD_AME" id="DCD"> BEN_DCD_AME <span class="infobulle" aria-label="Année et mois de décès du bénéficiaire (rien si vivant|201701)"><img src="https://www.schenk-systeme.ch/npweb/wp-content/uploads/2018/03/info.png" width="25px" height="25px"></span></label>
                <input type="text" name="BEN_DCD_AME">
                <br>
                <br>

                <input type="submit" name="submit" value="Ajouter">
                <br>
                <br>

            </form>

        </div>

        <div class="col formulaireList listElem">
            <h1>Tous les bénéficiaires</h1>
            <?php

            $db = new SQLite3("../bdd/base.db");
            $currentPage = intval($_GET['page']) - 1 ;
            $res = $db->query("SELECT COUNT(*) as n FROM EB_INB_F")->fetchArray();
            $max = intval($res['n'] / 20);
            if(!is_numeric($_GET['page'])) header("Location:?page=1");
            if($currentPage > $max) header("Location:?page=$max");

            $i = 0;
            echo "<div class='numerotation'>";
                if($currentPage >= 1){
                    echo "<a href='?page=".($currentPage)."'> <span> &#8592; </span> </a>";
                }

                if($currentPage+1 <= 0){
                    header("Location:beneficiaire.php?page=1");
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
                <h1>Modifier un bénéficiaire</h1>
                <form name="allUpdate" id="formBenUpdate">
                    <label for="BEN_NIR_IDT"> BEN_NIR_IDT </label>
                    <input type="text" name="BEN_NIR_IDT_UPDATE" id="BEN_NIR_IDT_UPDATE" readonly="true">

                    <label for="BEN_NAI_ANN"> BEN_NAI_ANN </label>
                    <input type="text" name="BEN_NAI_ANN_UPDATE" id="BEN_NAI_ANN_UPDATE">

                    <label for="BEN_RES_DPT"> BEN_RES_DPT </label>
                    <input type="text" name="BEN_RES_DPT_UPDATE" id="BEN_RES_DPT_UPDATE">

                    <label for="BEN_SEX_COD"> BEN_SEX_COD </label>
                    <input type="text" name="BEN_SEX_COD_UPDATE" id="BEN_SEX_COD_UPDATE">

                    <label for="BEN_DCD_AME"> BEN_DCD_AME </label>
                    <input type="text" name="BEN_DCD_AME_UPDATE" id="BEN_DCD_AME_UPDATE">
                    <br>
                    <br>

                    <input type="submit" name="submit" value="Modifier">
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
