<?php 
date_default_timezone_set('Europe/Paris');

require_once('../config/config.inc.php');
require_once('../config/connexion_sql.inc.php');
require_once('../model/model.php');
require_once('../model/inventories.php');

//On initialise le lien avec la BDD pour les modèles
/**
 * @var \PDO $bdd
 */
Model::set_db($bdd);

$inventory = Inventories::getById($_GET['produit']);

?>
<div class="modal-header">
    <h5 class="modal-title"><?= $inventory->description(); ?></h5>
</div>
<form method="POST" enctype="multipart/form-data" action="index.php?page=<?= $_GET['page']; ?>&ctrl=inventaire&action=modifier">
    <input type="hidden" name="id_product" value="<?= $inventory->id(); ?>">
    <div class="modal-body">
        <label for="description_produit" class="form-label">Description</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="description_produit" name="description" value="<?= $inventory->description(); ?>">
        </div>
        <label for="prix_produit" class="form-label">Prix</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="prix_produit" name="price" value="<?= $inventory->price()/100; ?>">
        </div>
        <div>
            <label for="picture" class="form-label">Modifier photo</label>
            <input class="form-control" type="file" id="picture" name="picture">
        </div>
        <div class="inventory-img-container">
            <img src="<?= $inventory->picture(); ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
        <button class="btn btn-primary btn-ajouter" type="submit">Modifier</button>
        <button class="btn btn-primary btn-ajouter-loading" type="button" style="display: none;" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Modification en cours...
        </button>
    </div>
</form>