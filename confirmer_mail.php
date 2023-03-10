<?php
session_start();

date_default_timezone_set('Europe/Paris');

require_once('config/config.inc.php');
require_once('config/connexion_sql.inc.php');
require_once('model/model.php');
require_once('model/users.php');

//On initialise le lien avec la BDD pour les modèles
Model::set_db($bdd);

$user = Users::getById($_GET['user']);
$user->confirmAccount();
$user->save();

$_SESSION['user'] = $user->id();

$_SESSION['success_message'] = "Votre adresse mail a bien été confirmée, votre compte est désormais actif.";
header('Location: index.php?ctrl=pages&action=afficher');
exit;