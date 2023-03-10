<?php

require_once('model/users.php');
require_once('model/members.php');
require_once('model/ranks.php');
require_once('model/pages.php');
require_once('model/gestionmail.php');
require_once('model/invitations.php');
require_once('config/config.inc.php');

class UtilisateursController
{
    public function post_connexion()
    {
        $mail = (isset($_POST['mail'])) ? $_POST['mail'] : null;
        $pass = (isset($_POST['pass'])) ? $_POST['pass'] : null;

        if ($mail != null && $pass != null)
        {
            $u = Users::getByMail($mail);
            if ($u != null)
            {
                if (password_verify($pass, $u->password()))
                {
                    if ($u->confirmed() == 1)
                    {
                        $_SESSION['user'] = $u->id();
                        $pages = Members::countByUser($_SESSION['user']);
                        if ($pages == 1)
                        {
                            $member = Members::getByUser($_SESSION['user']);
                            foreach ($member as $m)
                            {
                                $p = Pages::getById($m->id_page());
                            }
                            header('Location: index.php?page='.$p->uuid().'&ctrl=finances&action=afficher');
                            exit;
                        }
                        else
                        {
                            header('Location: index.php?ctrl=pages&action=afficher');
                            exit;
                        }
                    }
                    else
                    {
                        $_SESSION['error_message'] = "Vous devez confirmer votre adresse mail en cliquant sur le lien reçu par mail afin de vous connecter.";
                        $_SESSION['mail'] = $mail;
                        header('Location: connexion.php');
                        exit;
                    }
                }
                else
                {
                    $_SESSION['error_message'] = "Aucun compte ne correspond aux informations rentrées.";
                    $_SESSION['mail'] = $mail;
                    header('Location: connexion.php');
                    exit;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Aucun compte ne correspond aux informations rentrées.";
                $_SESSION['mail'] = $mail;
                header('Location: connexion.php');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez renseigner vos informations de connexion.";
            $_SESSION['mail'] = $mail;
            header('Location: connexion.php');
            exit;
        }
    }

    public function post_inscription()
    {
        $name = (isset($_POST['name'])) ? $_POST['name'] : null;
        $first_name = (isset($_POST['first_name'])) ? $_POST['first_name'] : null;
        $mail = (isset($_POST['mail'])) ? $_POST['mail'] : null;
        $pass = (isset($_POST['pass'])) ? $_POST['pass'] : null;
        $pass_confirm = (isset($_POST['pass_confirm'])) ? $_POST['pass_confirm'] : null;

        if ($name != null && $first_name != null && $mail != null && $pass != null && $pass_confirm != null)
        {
            if ($pass == $pass_confirm)
            {
                $rand = rand(1, 9);
                $profile_picture = 'assets/img/gg'.$rand.'.jpg';

                $u = Users::insertUser(strtoupper($name), ucwords($first_name), $mail, password_hash($pass, PASSWORD_BCRYPT), $profile_picture, date('Y-m-d H:i:s'));

                if (isset($_POST['invitation']))
                {
                    $_SESSION['user'] = $u->id();

                    $invitation = Invitations::getById($_POST['invitation']);
                    Members::insertMember($invitation->id_page(), $_SESSION['user']);
                    Ranks::insertRank($invitation->id_page(), $_SESSION['user'], "Membre", 0, 0, 0);

                    //On désactive l'invitation
                    $invitation->disableInvitation();
                    $invitation->save();

                    //On active automatiquement l'utilisateur
                    $u->confirmAccount();
                    $u->save();
                    
                    $_SESSION['success_message'] = "Votre accès à la page est désormais actif.";
                    header('Location: index.php?page='.Pages::getById($invitation->id_page())->uuid().'ctrl=finances&action=afficher');
                    exit;
                }
                else
                {
                    GestionMail::confirmation($u);
                    $_SESSION['success_message'] = "Votre compte a bien été crée, veuillez cliquer sur le lien que vous venez de recevoir par mail afin de confirmer votre adresse mail.";
                    header('Location: inscription.php');
                    exit;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Les deux mots de passe doivent être identiques.";
                $_SESSION['name'] = $name;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['mail'] = $mail;
                header('Location: inscription.php');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
            $_SESSION['name'] = $name;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['mail'] = $mail;
            header('Location: inscription.php');
            exit;
        }
    }

    public function post_modifier_rang()
    {
        $label = (isset($_POST['rank_label'])) ? $_POST['rank_label'] : null;

        if ($label != null)
        {
            $finances = (isset($_POST['finances'])) ? 1 : 0;
            $inventory = (isset($_POST['inventory'])) ? 1 : 0;
            $settings = (isset($_POST['settings'])) ? 1 : 0;

            $rank = Ranks::getByUserAndPage($_GET['id_utilisateur'], Pages::getByUuid($_GET['page'])->id());
            $rank->modifyRank($label, $finances, $inventory, $settings);
            $rank->save();

            $_SESSION['success_message'] = "Les informations du membre ont bien été mises à jour.";
            header('Location: '.$_POST['redirection']);
            exit;
        }
        else
        {
            $_SESSION['error_message'] = "Le rang ne doit pas être vide, veuillez renseigner un rang avant de valider.";
            header('Location: '.$_POST['redirection']);
            exit;
        }
    }

    public function post_reset_password()
    {
        $mail = (isset($_POST['mail'])) ? $_POST['mail'] : null;

        if ($mail != null)
        {
            if (filter_var($mail, FILTER_VALIDATE_EMAIL))
            {
                $u = Users::getByMail($mail);
                if ($u != null)
                {
                    $_SESSION['success_message'] = "Le mail contenant la démarche à suivre vous a été envoyé à l'adresse $mail. Vous disposez de 24h pour valider cette dernière.";
                    GestionMail::reset_password($mail, $u);
                }
                else
                {
                    $_SESSION['error_message'] = "Aucun compte ne correspond à l'adresse mail renseignée.";
                    $_SESSION['mail'] = $mail;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Veuillez renseigner une adresse mail valide.";
                $_SESSION['mail'] = $mail;
            }
        }
        else
            $_SESSION['error_message'] = "Veuillez renseigner une adresse mail avant de valider.";
        
        header('Location: mdp_oublie.php');
        exit;
    }

    public function post_deconnexion()
    {
        session_destroy();
        header('Location: connexion.php');
        exit;
    }
}