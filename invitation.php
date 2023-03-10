<?php 
session_start();

date_default_timezone_set('Europe/Paris');

require_once('config/config.inc.php');
require_once('config/connexion_sql.inc.php');
require_once('model/model.php');
require_once('model/invitations.php');
require_once('model/users.php');
require_once('model/members.php');
require_once('model/pages.php');
require_once('model/ranks.php');

//On initialise le lien avec la BDD pour les modèles
Model::set_db($bdd);

$uuid_invitation = $_GET['invitation'];
$invitation = Invitations::getByUuid($uuid_invitation);

if ($invitation != null)
{
    if ($invitation->active() == 1)
    {
        if (Users::getByMail($invitation->mail()) != null)
        {
            //On connecte automatiquement l'utilisateur puis on ajoute ses informations
            //pour l'accès à la page
            $_SESSION['user'] = Users::getByMail($invitation->mail())->id();

            Members::insertMember($invitation->id_page(), $_SESSION['user']);
            Ranks::insertRank($invitation->id_page(), $_SESSION['user'], "Membre", 0, 0, 0);

            //On désactive l'invitation
            $invitation->disableInvitation();
            $invitation->save();
            
            $_SESSION['success_message'] = "Votre accès à la page est désormais actif.";
            header('Location: index.php?page='.Pages::getById($invitation->id_page())->uuid().'ctrl=finances&action=afficher');
            exit;
        }
        else
        {
            header('Location: inscription.php?invitation='.$invitation->uuid());
            exit;
        }
    }
    else
    {
        $_SESSION['error_message'] = "Cette invitation n'est plus active.";
        header('Location: index.php');
        exit;
    }
}
else
{
    $_SESSION['error_message'] = "Cette invitation n'est pas valide.";
    header('Location: index.php');
    exit;
}
?>