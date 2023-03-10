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
        <div class="login__container reset-password">
            <?php 
            if (isset($_SESSION['success_message'])) :
            ?>
                <div class="logo">
                    <img src="assets/img/mail_sent.svg">
                </div>
                <h1>Mail envoyé</h1>
                <p>
                    <?= $_SESSION['success_message']; ?>
                </p>
                <?php unset($_SESSION['success_message']); ?>
            <?php
            else :
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
                    <img src="assets/img/forgot.svg">
                </div>
                <h1>Mot de passe oublié ?</h1>
                <p>Renseignez l'adresse mail utilisée lors de votre inscription, la procédure à suivre vous y sera envoyée.</p>
                <form method="POST" action="index.php?ctrl=utilisateurs&action=reset_password">
                    <label for="mail" class="form-label">Adresse mail</label>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" id="mail" name="mail" <?php if (isset($_SESSION['mail'])) : ?>value="<?= $_SESSION['mail']; ?>"<?php unset($_SESSION['mail']); endif; ?>>
                    </div>
                    <button class="btn btn-primary">Réinitialiser le mot de passe</button>
                </form>
            <?php
                endif;
            ?>
        </div>
    </div>
</body>
</html>