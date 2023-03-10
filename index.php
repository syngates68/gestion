<?php 
session_start();

date_default_timezone_set('Europe/Paris');

require_once('config/config.inc.php');
require_once('config/connexion_sql.inc.php');
require_once('model/model.php');
require_once('model/users.php');
require_once('model/members.php');
require_once('model/pages.php');
require_once('model/ranks.php');

//On initialise le lien avec la BDD pour les modèles
Model::set_db($bdd);

$content = null;
if (isset($_GET['ctrl']) && isset($_GET['action']))
{
    $canAccess = true;
    if (isset($_GET['page']))
    {
        if (isset($_SESSION['user']))
        {
            if (Members::countByUserAndPage($_SESSION['user'], Pages::getByUuid($_GET['page'])->id()) == 0)
                $canAccess = false;
        }
        else
            $canAccess = false;
    }
    
    if ($canAccess)
    {
        $ctrl_file = 'controller/'.$_GET['ctrl'].'.inc.php';
        if (file_exists($ctrl_file))
        {
            require_once $ctrl_file;
    
            $ctrl_class = ucfirst($_GET['ctrl']).'Controller';
            if (class_exists($ctrl_class))
            {
                $c = new $ctrl_class();
    
                $method = $_SERVER['REQUEST_METHOD'].'_'.$_GET['action'];
                if (method_exists($c, $method))
                {
                    if (strtolower($_SERVER['REQUEST_METHOD']) == 'get')
                    {
                        ob_start();
                        $c->$method();
                        $content = ob_get_clean();
                    }
                    else
                        $c->$method();
                }
            }
        }
    }
    else
    {
        if (isset($_SESSION['user']))
        {
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
            header('Location: connexion.php');
            exit;
        }
    }
}
else
{
    if (isset($_SESSION['user']))
    {
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
        header('Location: connexion.php');
        exit;
    }
}

//Si l'utilisateur demande à accéder à une page nécessitant une connexion
//et un droit d'accès, on fait toutes les vérifications nécessaires
$isConnected = false;
$canAccess = false;
if ($content != null)
{
    if (isset($_SESSION['user']))
    {
        $isConnected = true;
        //Ajout de la vérification des droits d'accès au groupe
    }
}

if ($isConnected) :
    $u = Users::getById($_SESSION['user']);
    $p = null;
    if (isset($_GET['page']))
    {
        $p = Pages::getByUuid($_GET['page']);
        $r = Ranks::getByUserAndPage($_SESSION['user'], $p->id());
    }

    if (!isset($_GET['mode'])) :
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/main.js" defer></script>
</head>
<body>
    <?php $link = ($p != null) ? 'index.php?page='.$p->uuid().'&ctrl=finances&action=afficher' : 'index.php?ctrl=pages&action=afficher'; ?>
    <nav class="navbar">
        <div class="container navbar__container">
            <a class="navbar-brand" href="<?= $link; ?>"><img src="assets/img/logo.png"></a>
            <div class="navbar__container-cta">
                <div class="username_container">
                    <div class="username"><?= $u->first_name().' '.$u->name(); ?></div>
                    <?php if ($p != null) : ?>
                        <div class="rang"><?= $r->label(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="userimg__container dropdown">
                    <img src="<?= $u->profile_picture(); ?>" href="#" class="dropdown-toggle" role="button" id="user_menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu user_menu_container" aria-labelledby="user_menu">
                        <li class="user-informations__container">
                            <span class="material-icons">person_outline</span>
                            <div class="user-informations">
                                <div class="mail"><?= $u->mail(); ?></div>
                                <a href="#" class="no-hover">Modifier le profil
                            </div>
                        </li>
                        <li><a class="dropdown-item" href="index.php?ctrl=pages&action=afficher">Vos groupes</a></li>
                        <li><a class="dropdown-item" href="#">Paramètres du compte</a></li>
                        <li><form method="POST" action="index.php?ctrl=utilisateurs&action=deconnexion"><button type="submit">Déconnexion</button></form></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <?php if ($p != null) : ?>
    <!-- Modal d'invitation -->
    <div class="modal fade" id="modal_invitation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inviter à rejoindre le groupe</h5>
                    <img class="illustration" src="assets/img/join.svg">
                </div>
                <form method="POST" action="index.php?page=<?= $p->uuid(); ?>&ctrl=pages&action=inviter">
                    <input type="hidden" name="redirection" value="<?= $_SERVER['REQUEST_URI']; ?>">
                    <div class="modal-body">
                        <label for="email" class="form-label">Adresse mail de la personne à inviter</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="email" name="mail">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary btn-inviter" type="submit">Envoyer l'invitation</button>
                        <button class="btn btn-primary btn-inviter-loading" type="button" style="display: none;" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Envoi en cours...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal membres -->
    <div class="modal fade" id="modal_membres" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Membres du groupe</h5>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <?php foreach (Members::getAllByPage($p->id()) as $m) : ?>
                                <tr>
                                    <td>
                                        <div class="member">
                                            <img src="<?= Users::getById($m->id_user())->profile_picture(); ?>">
                                            <div class="memberinformations">
                                                <div class="membername"><?= Users::getById($m->id_user())->first_name().' '.Users::getById($m->id_user())->name(); ?></div>
                                                <div class="memberrank"><?= Ranks::getByUserAndPage($m->id_user(), $m->id_page())->label(); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php if ($p->id_creator() == $_SESSION['user']) : ?>
                                        <td class="text-end">
                                            <a href="#" class="modify" data-id="<?= $m->id(); ?>"><span class="material-icons">edit_note</span></a>
                                            <a href="#" class="delete"><span class="material-icons">delete_outline</span></a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <tr class="tr-modify-member" id="modify_member_<?= $m->id(); ?>" style="display: none;">
                                    <td colspan="2">
                                        <form method="POST" action="index.php?page=<?= $p->uuid(); ?>&ctrl=utilisateurs&action=modifier_rang&id_utilisateur=<?= $m->id_user(); ?>">
                                            <input type="hidden" name="redirection" value="<?= $_SERVER['REQUEST_URI']; ?>">
                                            <div class="member-rank"><span class="member-rank__label">Rang :</span> <?= Ranks::getByUserAndPage($m->id_user(), $m->id_page())->label(); ?> <a href="#" class="modify_label" data-id="<?= $m->id(); ?>"><span class="material-icons">edit</span></a></div>
                                            <div class="input-group mb-3" id="rank_label_<?= $m->id(); ?>" style="display: none;">
                                                <input type="text" class="form-control" name="rank_label" value="<?= Ranks::getByUserAndPage($m->id_user(), $m->id_page())->label(); ?>">
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="finances_rights" name="finances" <?= (Ranks::getByUserAndPage($m->id_user(), $m->id_page())->finances() == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="finances_rights">
                                                    Finances
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="inventory_rights" name="inventory" <?= (Ranks::getByUserAndPage($m->id_user(), $m->id_page())->inventory() == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="inventory_rights">
                                                    Inventaire
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="settings_rights" name="settings" <?= (Ranks::getByUserAndPage($m->id_user(), $m->id_page())->settings() == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="settings_rights">
                                                    Paramètres
                                                </label>
                                            </div>
                                            <div class="members-rank__footer text-end">
                                                <button class="btn btn-outline-primary btn-annuler" type="button" data-id="<?= $m->id(); ?>">Annuler</button>
                                                <button class="btn btn-primary" type="submit">Modifier</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    
    <nav class="navbar navbar-secondary">
        <div class="container navbar__container">
            <div class="group-informations">
                <img src="<?= $p->picture(); ?>" class="group-img-navbar">
                <div class="group-title"><?= $p->name(); ?></div>
            </div>
            <div class="navbar__container-cta">
                <div class="username_container">
                    <div class="username"><?= $u->first_name().' '.$u->name(); ?></div>
                    <div class="rang"><?= $r->label(); ?></div>
                </div>
                <div class="userimg__container dropdown">
                    <img src="<?= $u->profile_picture(); ?>" href="#" class="dropdown-toggle" role="button" id="user_menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu user_menu_container" aria-labelledby="user_menu">
                        <li class="user-informations__container">
                            <span class="material-icons">person_outline</span>
                            <div class="user-informations">
                                <div class="mail"><?= $u->mail(); ?></div>
                                <a href="#" class="no-hover">Modifier le profil
                            </div>
                        </li>
                        <li><a class="dropdown-item" href="index.php?ctrl=pages&action=afficher">Vos groupes</a></li>
                        <li><a class="dropdown-item" href="#">Paramètres du compte</a></li>
                        <li><form method="POST" action="index.php?ctrl=utilisateurs&action=deconnexion"><button type="submit">Déconnexion</button></form></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container app-container">
        
        <div class="header">
            <div class="header__left">
                <div class="groupimg__container">
                    <img src="<?= $p->picture(); ?>">
                </div>
            </div>
            <div class="header__right">
                <div class="infos">
                    <div class="group-title"><?= $p->name(); ?></div>
                    <div class="group-owner">Administré par <?= Users::getById($p->id_creator())->first_name().' '.Users::getById($p->id_creator())->name(); ?></div>
                    <div class="group-members"><a href="#" data-bs-toggle="modal" data-bs-target="#modal_membres"><?= Members::countByPage($p->id()); ?> membre<?php if (Members::countByPage($p->id()) > 1) : ?>s<?php endif; ?></a></div>
                </div>
                <?php if ($p->id_creator() == $u->id()) : ?>
                <div class="cta">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_invitation">Inviter</button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <div class="content__left">
                <div class="content__left-title">
                    <img src="assets/img/favicon_blue.png">
                    <p class="title">Menu principal</p>
                </div>
                <ul class="nav flex-column main-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if (stripos($_SERVER['REQUEST_URI'], 'finances') !== false) : ?>active<?php endif; ?>" href="index.php?page=<?= $p->uuid(); ?>&ctrl=finances&action=afficher">
                            <span class="material-icons-outlined">account_balance</span>
                            Finances
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (stripos($_SERVER['REQUEST_URI'], 'inventaire') !== false) : ?>active<?php endif; ?>" href="index.php?page=<?= $p->uuid(); ?>&ctrl=inventaire&action=afficher">
                            <span class="material-icons-outlined">shopping_bag</span>
                            Inventaire
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (stripos($_SERVER['REQUEST_URI'], 'parametres') !== false) : ?>active<?php endif; ?>" href="index.php?page=<?= $p->uuid(); ?>&ctrl=parametres&action=afficher">
                            <span class="material-icons-outlined">settings</span>
                            Paramètres
                        </a>
                    </li>
                </ul>
            </div>
            <div class="content__right">
                <div class="bloc-page">
                    <?= $content; ?>
                </div>
            </div>
        </div>

    </div>
    <?php else : ?>
        <div class="container app-container">
            <?= $content; ?>
        </div>
    <?php endif; ?>
</body>
</html>
<?php
    else :
        echo $content;
    endif;
else :
    header('Location: connexion.php');
    exit;
endif;