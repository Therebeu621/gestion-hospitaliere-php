<?php session_start();
require __DIR__ . '/cryptage.php';

    $db = new SQLite3('bdd/user.db');

    if(isset($_POST['logout'])) session_destroy();
    ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>DocInfos</title>
    <?php //add styles
    include('pages/includeFiles/styleIndex.html');
    ?>
    <?php //add bootstrap's scripts
    include('pages/includeFiles/bootstrapScripts.html');
    ?>
</head>
<body>

<br>
<?php
    if(!isset($_SESSION['login'])){ ?>
<div class="formulaire">
    <h1>Connexion</h1>
    <form method="post" name="affection" id="formAddAffection" action="index.php">
    <label for="user"> Login </label>
    <input type="text" name="user" placeholder="Identifiant"> <br/>

    <label for="password"> Password </label>
    <input type="password" placeholder="********" name="pwd"> <br/>
        <br>

    <input type="submit" name="submit" value="Connexion">
        <br>
        <br>

    </form>
</div>

<?php }
    else{ ?>

        <h1>Bonjour <?php echo $_SESSION['login']; ?></h1>


        <div class="buttonIndex">
            <button class="btn btn-primary btn-block" id="beneficiaire">Bénéficiaire</button>
            <button class="btn btn-primary btn-block" id="prestation">Prestation</button>
            <button class="btn btn-primary btn-block" id="affection">Affection</button>
            <form action="index.php" method="post">
            <input type="submit" class="btn btn-primary btn-block" id="deconnexion" name="logout" value="Déconnexion">
            </form>
        </div>

        <?php
        if(isset($_POST["logout"])){
            session_destroy();
            header("Location: ./index.php");
        }
        ?>


    <?php } ?>

<?php
    if(isset($_POST['submit'])){
        $unsafe = $_POST['user'];
        $safe = SQLITE3::escapeString($unsafe);
        $user = $db->query("SELECT login FROM user WHERE login ='$safe'")->fetchArray();
        if(empty($user['login'])) echo "Veuillez contacter l'administrateur pour vous créer un compte \n <a href='mailto:admin@example.fr'> (admin@example.fr) </a>";
        $res = $db->query("SELECT password FROM user WHERE login ='$safe'")->fetchArray();
        if($_POST['pwd'] == decrypt($res['password']) && !empty($_POST['pwd']) && !empty($res['password'])){
            $_SESSION['login'] = $_POST['user'];
            header("Refresh:0");
        }
        else{
            echo "\nMot de passe invalide ! Veuillez réessayer sinon contacter l'administrateur.";
        }
    }
    if(isset($_POST['logout'])){
        session_destroy();
        header("Refresh:0");
    }
?>
</body>
<foot>

</foot>

<script>
    $(document).on('click','#beneficiaire',function(){
        
        window.location.href="pages/beneficiaire.php?page=1";
    });

    $(document).on("click","#prestation",function(){
        window.location.href="pages/prestations.php?page=1";
    });

    $(document).on("click","#affection",function(){
        window.location.href="pages/affectation.php?page=1";
    });
</script>
</html>
