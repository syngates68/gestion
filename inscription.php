<?php
session_start();

require_once('config/config.inc.php');
require_once('config/connexion_sql.inc.php');
require_once('model/model.php');
require_once('model/invitations.php');

//On initialise le lien avec la BDD pour les modèles
Model::set_db($bdd);

if (isset($_GET['invitation']))
{
    $uuid_invitation = $_GET['invitation'];
    $invitation = Invitations::getByUuid($uuid_invitation);
}
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
            <?php if (isset($_SESSION['success_message'])) : ?>
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
                    <img src="assets/img/logo.png">
                </div>
                <h1>Inscription</h1>
                <?php if (isset($_GET['invitation'])) : ?>
                    <div class="alert alert-info">
                        Afin de pouvoir accéder à la page où vous avez été invité, veuillez renseigner
                        les informations ci-dessous qui permettront de créer votre compte sur la plateforme.
                    </div>
                <?php endif; ?>
                <form method="POST" action="index.php?ctrl=utilisateurs&action=inscription">
                    <?php if (isset($_GET['invitation'])) : ?>
                        <input type="hidden" name="invitation" value="<?= $invitation->id(); ?>">
                    <?php endif; ?>
                    <label for="name" class="form-label">Nom</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="name" name="name" <?php if (isset($_SESSION['name'])) : ?>value="<?= $_SESSION['name']; ?>"<?php unset($_SESSION['name']); endif; ?>>
                    </div>
                    <label for="first_name" class="form-label">Prénom</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="first_name" name="first_name" <?php if (isset($_SESSION['first_name'])) : ?>value="<?= $_SESSION['first_name']; ?>"<?php unset($_SESSION['first_name']); endif; ?>>
                    </div>
                    <?php if (!isset($_GET['invitation'])) : ?>
                        <label for="mail" class="form-label">Adresse mail</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="mail" name="mail" <?php if (isset($_SESSION['mail'])) : ?>value="<?= $_SESSION['mail']; ?>"<?php unset($_SESSION['mail']); endif; ?>>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="mail" value="<?= $invitation->mail(); ?>">
                    <?php endif; ?>
                    <label for="pass" class="form-label">Mot de passe</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pass" name="pass">
                    </div>
                    <label for="pass_confirm" class="form-label">Confirmation du mot de passe</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pass_confirm" name="pass_confirm">
                    </div>
                    <button class="btn btn-primary">S'inscrire</button>
                </form>
                <div class="text-center information-message">Vous avez déjà un compte ? Connectez-vous <a href="connexion.php">ici</a></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>