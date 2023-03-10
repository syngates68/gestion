<?php

require_once('model/pages.php');

class ParametresController
{
    public function get_afficher()
    {
        $p = Pages::getByUuid($_GET['page']);
        $rank = Ranks::getByUserAndPage($_SESSION['user'], $p->id());
        include 'view/parametres/afficher.inc.php';
    }

    public function post_modifier()
    {
        $p = Pages::getByUuid($_GET['page']);

        if (!empty($_FILES['group_picture']['name']))
        {
            $tabPhoto = explode('.', basename($_FILES['group_picture']['name']));
            $name = $tabPhoto[0];
            $ext = end($tabPhoto);
            $newName = $p->id().'_'.$name.'.'.$ext;

            $uploadfile = 'assets/img/pages/'.$newName;

            move_uploaded_file($_FILES['group_picture']['tmp_name'], $uploadfile);

            $p->set_picture($uploadfile);
        }

        $p->modifyPage($_POST['group_name']);
        $p->save();

        $_SESSION['success_message'] = "Les modifications ont bien été faites.";
        header('Location: index.php?page='.$_GET['page'].'&ctrl=parametres&action=afficher');
        exit;
    }
}