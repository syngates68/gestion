<!-- Modal ajout produit -->
<div class="modal fade" id="modal_ajout_produit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajout d'un produit à l'inventaire</h5>
                <img class="illustration" src="assets/img/inventory.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=inventaire&action=ajouter">
                <div class="modal-body">
                    <label for="description_produit" class="form-label">Description</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="description_produit" name="description">
                    </div>
                    <label for="prix_produit" class="form-label">Prix</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="prix_produit" name="price">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_garment" id="is_garment" checked>
                        <label class="form-check-label" for="is_garment">
                            Il s'agit d'un vêtement
                        </label>
                    </div>
                    <div class="sizes-product">
                        <label>Sélectionner les tailles pour ce produit :</label>
                        <?php foreach ($sizes as $s) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="size[]" value="<?= $s->id(); ?>" id="size_<?= $s->id(); ?>">
                                <label class="form-check-label" for="size_<?= $s->id(); ?>">
                                    <?= $s->label(); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="stock-product" style="display: none;">
                        <label for="stock_produit" class="form-label">Stock</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" id="stock_produit" name="stock">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary btn-ajouter" type="submit">Ajouter</button>
                    <button class="btn btn-primary btn-ajouter-loading" type="button" style="display: none;" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Ajout en cours...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal modification produit -->
<div class="modal fade" id="modal_modifier_produit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        </div>
    </div>
</div>

<!-- Modal entrée stock -->
<div class="modal fade" id="modal_entree_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Entrée de stock</h5>
                <img class="illustration" src="assets/img/get_stock.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=inventaire&action=entree_stock">
                <div class="modal-body">
                    <?php if ($inventory != null) : ?>
                        <label for="product" class="form-label">Produit</label>
                        <div class="input-group mb-3">
                            <select class="form-select" id="product" name="product">
                            <?php foreach ($inventory as $i) : ?>
                                <?php if (!empty($i->sizes())) : ?>
                                    <?php for ($j = 0; $j < sizeof($i->sizes()); $j++) : ?>
                                        <?php $size = $i->sizes()[$j]; ?>
                                        <option value="<?= $i->id(); ?>_<?= $size->id(); ?>"><?= $i->description().' Taille '.Sizes::getById($size->id_size())->label(); ?></option>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <option value="<?= $i->id(); ?>"><?= $i->description(); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <span class="material-icons">info_outline</span>
                            Aucun produit n'a encore été ajouté dans l'inventaire.
                        </div>
                    <?php endif; ?>
                    <?php if ($inventory != null) : ?>
                        <label for="stock_produit" class="form-label">Stock</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" id="stock_produit" name="stock">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                    <?php if ($inventory != null) : ?>
                        <button class="btn btn-primary btn-ajouter" type="submit">Ajouter</button>
                        <button class="btn btn-primary btn-ajouter-loading" type="button" style="display: none;" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Ajout en cours...
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal sortie stock -->
<div class="modal fade" id="modal_sortie_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sortie de stock</h5>
                <img class="illustration" src="assets/img/stock_out.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=inventaire&action=sortie_stock">
                <div class="modal-body">
                    <?php if ($inventory != null) : ?>
                        <label for="product" class="form-label">Produit</label>
                        <div class="input-group mb-3">
                            <select class="form-select" id="product" name="product">
                            <?php foreach ($inventory as $i) : ?>
                                <?php if (!empty($i->sizes())) : ?>
                                    <?php for ($j = 0; $j < sizeof($i->sizes()); $j++) : ?>
                                        <?php $size = $i->sizes()[$j]; ?>
                                        <option value="<?= $i->id(); ?>_<?= $size->id(); ?>"><?= $i->description().' Taille '.Sizes::getById($size->id_size())->label(); ?></option>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <option value="<?= $i->id(); ?>"><?= $i->description(); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <span class="material-icons">info_outline</span>
                            Aucun produit n'a encore été ajouté dans l'inventaire.
                        </div>
                    <?php endif; ?>
                    <?php if ($inventory != null) : ?>
                        <label for="stock_produit" class="form-label">Stock à retirer</label>
                        <div class="input-group mb-3">
                            <input type="number" min="1" class="form-control" id="stock_produit" name="stock">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                    <?php if ($inventory != null) : ?>
                        <button class="btn btn-primary btn-ajouter" type="submit">Ajouter</button>
                        <button class="btn btn-primary btn-ajouter-loading" type="button" style="display: none;" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Ajout en cours...
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="inventaire">
    <?php 
    if (isset($_SESSION['error_message'])) : 
    ?>
        <div class="alert alert-danger">
            <span class="material-icons">cancel</span>
            <?= $_SESSION['error_message']; ?>
        </div>
    <?php
    unset($_SESSION['error_message']);
    endif;

    if (isset($_SESSION['success_message'])) : 
        ?>
            <div class="alert alert-success">
                <span class="material-icons">check_circle_outline</span>
                <?= $_SESSION['success_message']; ?>
            </div>
        <?php
        unset($_SESSION['success_message']);
        endif; 
    ?>
    <?php if ($rank->inventory() == 1) : ?>
        <div class="inventaire__header">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_ajout_produit">Nouveau produit</button>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_entree_stock">Entrée stock</button>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal_sortie_stock">Sortie stock</button>
        </div>
    <?php endif; ?>

    <div class="inventaire-container">
        <?php if ($inventory != null) : ?>
            <div class="inventaire__content">
                <?php foreach ($inventory as $i) : ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-img">
                                <img src="<?= $i->picture(); ?>">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body__header">
                                <div class="card-title"><?= $i->description(); ?></div>
                                <div class="price"><?= $i->price() / 100; ?>€</div>
                            </div>
                            <?php $stock = $i->stock(); ?>
                            <?php if (!empty($i->sizes())) : ?>
                                <?php $stock = 0; ?>
                                <div class="sizes">
                                    <div class="size size-header">
                                        <div class="size-label">Taille</div>
                                        <div class="size-stock">Stock</div>
                                    </div>
                                    <?php foreach ($i->sizes() as $ps) : ?>
                                        <div class="size">
                                            <div class="size-label"><?= Sizes::getById($ps->id_size())->label(); ?></div>
                                            <div class="size-stock"><?= $ps->stock(); ?></div>
                                        </div>
                                        <?php $stock += $ps->stock(); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="stock">
                                <div class="stock-label">Stock total</div>
                                <div class="stock-number"><?= $stock; ?></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="modify" data-page="<?= $page->uuid(); ?>" data-id="<?= $i->id(); ?>" data-bs-toggle="modal" data-bs-target="#modal_modifier_produit"><span class="material-icons">edit_note</span></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="alert alert-info">
                <span class="material-icons">info_outline</span>
                Aucun produit n'a encore été ajouté dans l'inventaire.
            </div>
        <?php endif; ?>
    </div>
</div>