<?php

require_once('model/pages.php');
require_once('model/ranks.php');
require_once('model/finances.php');
require_once('model/inventories.php');
require_once('model/sizes.php');
require_once('model/productsizes.php');
require_once('config/config.inc.php');

class InventaireController
{
    public function get_afficher()
    {
        $page = Pages::getByUuid($_GET['page']);
        $rank = Ranks::getByUserAndPage($_SESSION['user'], $page->id());
        $inventory = Inventories::getAllByPage($page->id());
        $sizes = Sizes::getAll();
        include 'view/inventaire/afficher.inc.php';
    }

    public function post_ajouter()
    {
        $description = (isset($_POST['description'])) ? $_POST['description'] : null;
        $price = (isset($_POST['price'])) ? $_POST['price'] : null;
        $stock = (isset($_POST['stock'])) ? $_POST['stock'] : null;

        if ($description != null && $price != null)
        {
            //S'il s'agit d'un vêtement
            if (isset($_POST['is_garment']))
                $stock = 0;
            if (is_numeric($stock))
            {
                $price = str_replace(',', '.', $price);
                if (stripos($price, '.') !== false)
                {
                    $int = explode('.', $price)[0];
                    $dec = explode('.', $price)[1];
                    if (strlen($dec) == 1)
                        $dec .= 0;
                    $price = $int.'.'.$dec;
                }
                if (is_numeric($price))
                {
                    $price = $price * 100;
                    $inventory = Inventories::insertInventory(Pages::getByUuid($_GET['page'])->id(), ucwords($description), $price, $stock);
                    
                    //S'il s'agit d'un vêtement on insère toutes les tailles disponibles
                    if (isset($_POST['is_garment']))
                    {
                        $sizes = $_POST['size'];

                        for ($i = 0; $i < sizeof($sizes); $i++)
                        {
                            ProductSizes::insertProductSize($inventory->id(), $sizes[$i]);
                        }
                    }

                    $_SESSION['success_message'] = "Le produit a bien été ajouté à votre inventaire.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                    exit;
                }
                else
                {
                    $_SESSION['error_message'] = "Le prix doit être un chiffre ou un nombre à virgule.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                    exit;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Le stock doit être un chiffre.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
            exit;
        }
    }

    public function post_modifier()
    {
        $description = (isset($_POST['description'])) ? $_POST['description'] : null;
        $price = (isset($_POST['price'])) ? $_POST['price'] : null;

        if ($description != null && $price != null)
        {
            $price = str_replace(',', '.', $price);
            if (stripos($price, '.') !== false)
            {
                $int = explode('.', $price)[0];
                $dec = explode('.', $price)[1];
                if (strlen($dec) == 1)
                    $dec .= 0;
                $price = $int.'.'.$dec;
            }
            if (is_numeric($price))
            {
                $price = $price * 100;
                $inventory = Inventories::getById($_POST['id_product']);

                if (!empty($_FILES['picture']['name']))
                {
                    $tabPhoto = explode('.', basename($_FILES['picture']['name']));
                    $name = $tabPhoto[0];
                    $ext = end($tabPhoto);
                    $newName = $inventory->id().'_'.$name.'.'.$ext;
        
                    $uploadfile = 'assets/img/pages/'.$newName;
        
                    move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile);
        
                    $inventory->set_picture($uploadfile);
                }

                $inventory->modifyInventory(ucwords($description), $price, $inventory->stock());
                $inventory->save();
                
                $_SESSION['success_message'] = "Le produit a bien été modifié.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
            else
            {
                $_SESSION['error_message'] = "Le prix doit être un chiffre ou un nombre à virgule.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Tous les champs sont obligatoires.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
            exit;
        }
    }

    public function post_entree_stock()
    {
        $product = (isset($_POST['product'])) ? $_POST['product'] : null;
        $stock = (isset($_POST['stock'])) ? $_POST['stock'] : null;

        if ($product != null && $stock != null)
        {
            if (is_numeric($stock) && stripos($stock, '.') == false && stripos($stock, ',') == false)
            {
                //S'il s'agit d'un vêtement
                if (stripos($product, '_') !== false)
                {
                    $exp = explode('_', $product);
                    $productSize = ProductSizes::getById($exp[1]);
                    $productSize->modifyProductSize($stock);
                    $productSize->save();
                }
                else
                {
                    $inventory = Inventories::getById($product);
                    $inventory->modifyInventory($inventory->description(), $inventory->price(), $inventory->stock() + $stock);
                    $inventory->save();
                }

                $_SESSION['success_message'] = "L'entrée de stock a bien été prise en compte.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
            else
            {
                $_SESSION['error_message'] = "Le stock doit être un chiffre.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez choisir un produit et le stock souhaité.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
            exit;
        }
    }

    public function post_sortie_stock()
    {
        $product = (isset($_POST['product'])) ? $_POST['product'] : null;
        $stock = (isset($_POST['stock'])) ? $_POST['stock'] : null;

        if ($product != null && $stock != null)
        {
            if (is_numeric($stock) && stripos($stock, '.') == false && stripos($stock, ',') == false)
            {
                $inventory = Inventories::getById($product);
                if ($stock <= $inventory->stock())
                {
                    $inventory->modifyInventory($inventory->description(), $inventory->price(), $inventory->stock() - $stock);
                    if ($inventory->stock() - $stock == 0)
                        $inventory->disableInventory();
                    $inventory->save();
    
                    $_SESSION['success_message'] = "La sortie de stock a bien été prise en compte.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                    exit;
                }
                else
                {
                    $_SESSION['error_message'] = "Ce produit n'a que ".$inventory->stock()." élément(s) en stock, vous ne pouvez pas en sortir plus que cela.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                    exit; 
                }
            }
            else
            {
                $_SESSION['error_message'] = "Le stock doit être un chiffre.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez choisir un produit et le stock que vous souhaitez sortir.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=inventaire&action=afficher');
            exit;
        }
    }

    public function get_modifier()
    {
        include 'view/inventaire/modifier.inc.php';
    }
}