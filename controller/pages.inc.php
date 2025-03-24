<?php

require_once('model/members.php');
require_once('model/pages.php');
require_once('model/ranks.php');
require_once('model/users.php');
require_once('model/gestionmail.php');
require_once('model/invitations.php');
require_once('config/config.inc.php');

class PagesController
{
    public function get_afficher()
    {
        $members = Members::getByUser($_SESSION['user']);
        $pages = [];
        if ($members !== null)
        {
            foreach ($members as $m)
            {
                $p = Pages::getById($m->id_page());
                array_push($pages, new Pages($p->id(), $p->id_creator(), $p->name(), $p->picture(), $p->uuid(), $p->active()));
            }
        }
        include 'view/pages/afficher.inc.php';
    }

    public function post_creer()
    {
        $group_name = (isset($_POST['group_name'])) ? $_POST['group_name'] : null;
        
        if ($group_name != null)
        {
            $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $numbers = str_split('0123456789');
            $uuid = '';

            for ($i = 0; $i < 10; $i++)
            {
                $rand = rand(0, 1);
                if ($rand == 0)
                    $arr = $chars;
                else
                    $arr = $numbers;

                $uuid .= $arr[rand(0, (sizeof($arr) - 1))];
            }

            $group_name = ucwords($group_name);

            $page = Pages::insertPage($_SESSION['user'], $group_name, $uuid);
            Members::insertMember($page->id(), $_SESSION['user']);
            Ranks::insertRank($page->id(), $_SESSION['user'], "Administrateur", 1, 1, 1);
            
            $_SESSION['success_message'] = "Le groupe $group_name a bien été créé.";
            header('Location: index.php?ctrl=pages&action=afficher');
            exit;
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez renseigner le nom du groupe que vous souhaitez créer.";
            header('Location: index.php?ctrl=pages&action=afficher');
            exit;
        }
    }

    public function post_inviter()
    {
        $mail = (isset($_POST['mail'])) ? $_POST['mail'] : null;

        if ($mail != null)
        {
            if (filter_var($mail, FILTER_VALIDATE_EMAIL))
            {
                //On ajoute l'invitation en BDD
                $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
                $numbers = str_split('0123456789');
                $uuid = '';

                for ($i = 0; $i < 10; $i++)
                {
                    $rand = rand(0, 1);
                    if ($rand == 0)
                        $arr = $chars;
                    else
                        $arr = $numbers;

                    $uuid .= $arr[rand(0, (sizeof($arr) - 1))];
                }

                $invitation = Invitations::insertInvitation($mail, Pages::getByUuid($_GET['page'])->id(), $uuid, date('Y-m-d H:i:s'));
                GestionMail::invite($mail, Users::getById($_SESSION['user']), Pages::getByUuid($_GET['page']), $invitation);
                $_SESSION['success_message'] = "Un mail d'invitation vient d'être envoyé à $mail.";
            }
            else
                $_SESSION['error_message'] = "Veuillez renseigner une adresse mail avant de valider.";
        }
        else
            $_SESSION['error_message'] = "Veuillez renseigner une adresse mail avant de valider.";

        header('Location: '.$_POST['redirection']);
        exit;   
    }
}