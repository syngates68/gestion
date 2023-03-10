<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grogu - Logiciel de gestion</title>
    <link rel="icon" href="assets/img/favicon2.png">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body>
    <div class="login">
        <div class="login__container">
            <?php 
            if (isset($_SESSION['error_message'])) : 
            ?>
            <div class="alert alert-danger">
                <span class="material-icons">cancel</span>
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php 
            unset($_SESSION['error_message']);
            endif; 
            ?>
            <div class="logo">
                <img src="assets/img/logo.png">
            </div>
            <h1>Connexion</h1>
            <form method="POST" action="index.php?ctrl=utilisateurs&action=connexion">
                <label for="mail" class="form-label">Adresse mail</label>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="mail" name="mail" <?php if (isset($_SESSION['mail'])) : ?>value="<?= $_SESSION['mail']; ?>"<?php unset($_SESSION['mail']); endif; ?>>
                </div>
                <label for="pass" class="form-label">Mot de passe</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="pass" name="pass">
                </div>
                <a href="mdp_oublie.php" class="forgotten-password">Mot de passe oublié ?</a>
                <button class="btn btn-primary">Se connecter</button>
            </form>
            <div class="text-center information-message">Vous n'avez pas encore de compte ? Créez vous en un <a href="inscription.php">ici</a></div>
        </div>
    </div>
</body>
</html>