<?php
session_start();
require __DIR__ . '/cryptage.php';

$db = new SQLite3('bdd/user.db');
$loginError = '';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $unsafe = $_POST['user'] ?? '';
    $pwd = $_POST['pwd'] ?? '';
    $safe = SQLITE3::escapeString($unsafe);

    $row = $db->query("SELECT login, password FROM user WHERE login ='$safe'")->fetchArray(SQLITE3_ASSOC);
    if (empty($row['login'])) {
        $loginError = "Veuillez contacter l'administrateur pour vous creer un compte.";
    } elseif ($pwd !== '' && $pwd === decrypt($row['password'])) {
        $_SESSION['login'] = $row['login'];
        header('Location: index.php');
        exit;
    } else {
        $loginError = 'Mot de passe invalide. Veuillez reessayer.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>DocInfos</title>
    <?php
    include('pages/includeFiles/styleIndex.html');
    include('pages/includeFiles/bootstrapScripts.html');
    ?>
</head>
<body>

<br>
<?php if(!isset($_SESSION['login'])) { ?>
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
    <?php if ($loginError !== '') { ?>
        <p><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
</div>

<?php } else { ?>

        <h1>Bonjour <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8'); ?></h1>


        <div class="buttonIndex">
            <button class="btn btn-primary btn-block" id="beneficiaire">Beneficiaire</button>
            <button class="btn btn-primary btn-block" id="prestation">Prestation</button>
            <button class="btn btn-primary btn-block" id="affection">Affection</button>
            <form action="index.php" method="post">
            <input type="submit" class="btn btn-primary btn-block" id="deconnexion" name="logout" value="Deconnexion">
            </form>
        </div>


    <?php } ?>
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
