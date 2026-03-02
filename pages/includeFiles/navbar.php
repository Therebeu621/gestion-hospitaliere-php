<body>
<nav class="navbar navbar-expand-lg navbar-white bg-white">
    <div class="navbar-collapse collapse w-100 dual-collapse2 order-1 order-md-0">
        <ul class="navbar-nav ml-auto text-center">
            <li class="nav-item active">
                <a class="nav-link nbDatas" href="beneficiaire.php?page=1" target="_top">Bénéficiaires</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nbDatas" href="prestations.php?page=1" target="_top">Prestation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nbDatas" href="affectation.php?page=1" target="_top">Affectations</a>
            </li>
        </ul>
    </div>
    <div class="mx-auto my-2 order-0 order-md-1 position-relative">
        <a class="mx-auto" href="../index.php">
            <img src="https://cdn-icons-png.flaticon.com/512/387/387553.png" class="rounded-circle" width="70" height="70">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 dual-collapse2 order-2 order-md-2">
        <ul class="navbar-nav mr-auto text-center">
            <form class="form-inline my-2 my-lg-0" method="post" action="../index.php">
                <input id="recherche" class="form-control mr-sm-2" type="text" placeholder="Rechercher dans la BDD" aria-label="Search">
                <?php
                //check the user's session to import the logout possibility
                if(isset($_SESSION['login'])){
                ?>
                    <input type="submit" name="logout" value="Deconnexion" class='nav-link deconnexion'>
                    <?php
                }
                ?>
            </form>
        </ul>
    </div>
</nav>
</body>
