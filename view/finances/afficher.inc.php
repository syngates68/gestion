<!-- Modal entrée d'argent -->
<div class="modal fade" id="modal_entree" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Entrée d'argent</h5>
                <img class="illustration" src="assets/img/get_money.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=finances&action=entree">
                <input type="hidden" name="form_type" value="0">
                <div class="modal-body">
                    <ul class="nav choice-nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#" id="nav-merch-tab" data-bs-toggle="tab" data-bs-target="#nav-merch" type="button" role="tab" aria-controls="nav-merch" aria-selected="true">Merch</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="nav-autre-tab" data-bs-toggle="tab" data-bs-target="#nav-autre" type="button" role="tab" aria-controls="nav-autre" aria-selected="false">Autre</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-merch" role="tabpanel" aria-labelledby="nav-merch-tab">
                            <?php if ($inventory != null) : ?>
                                <?php foreach ($inventory as $i) : ?>
                                    <?php if (!empty($i->sizes())) : ?>
                                        <?php for ($j = 0; $j < sizeof($i->sizes()); $j++) : ?>
                                            <?php $size = $i->sizes()[$j]; ?>
                                            <input type="hidden" name="product[]" value="<?= $i->id().'_'.$size->id(); ?>">
                                            <div class="row" style="padding: 4px 0">
                                                <div class="col-lg-8">
                                                    <label for="quantite_<?= $i->id().'_'.$size->id(); ?>"><?= $i->description().' Taille '.Sizes::getById($size->id_size())->label(); ?></label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <input type="number" min="0" max="<?= $size->stock() ?>" class="form-control quantity" data-price="<?= $i->price() / 100; ?>" id="quantite_<?= $i->id().'_'.$size->id(); ?>" name="quantity[]" value="0">
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    <?php else : ?>
                                        <input type="hidden" name="product[]" value="<?= $i->id(); ?>">
                                        <div class="row" style="padding: 4px 0">
                                            <div class="col-lg-8">
                                                <label for="quantite_<?= $i->id(); ?>"><?= $i->description(); ?></label>
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="number" min="0" max="<?= $i->stock() ?>" class="form-control quantity" data-price="<?= $i->price() / 100; ?>" id="quantite_<?= $i->id(); ?>" name="quantity[]" value="0">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <label for="description_entree_libre" class="form-label">Description (facultative)</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="description_entree_libre" name="description_libre_merch">
                                </div>
                                <input type="hidden" class="form-control montant" id="montant_entree" name="montant" value="0">
                            <?php else : ?>
                                <div class="alert alert-info">
                                    <span class="material-icons">info_outline</span>
                                    Aucun produit n'a encore été ajouté dans l'inventaire.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="nav-autre" role="tabpanel" aria-labelledby="nav-autre-tab">
                            <label for="montant_entree_libre" class="form-label">Montant</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="montant_entree_libre" name="montant_libre">
                            </div>
                            <label for="description_entree_libre" class="form-label">Description</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="description_entree_libre" name="description_libre">
                            </div>
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

<!-- Modal sortie d'argent -->
<div class="modal fade" id="modal_sortie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sortie d'argent</h5>
                <img class="illustration" src="assets/img/pay.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=finances&action=sortie">
                <input type="hidden" name="form_type" value="0">
                <div class="modal-body">
                    <label for="montant_sortie_libre" class="form-label">Montant</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="montant_sortie_libre" name="montant_sortie_libre">
                    </div>
                    <label for="description_sortie_libre" class="form-label">Description</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="description_sortie_libre" name="description_sortie_libre">
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

<!-- Modal sortie apport -->
<div class="modal fade" id="modal_apport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apport</h5>
                <img class="illustration" src="assets/img/apport.svg">
            </div>
            <form method="POST" action="index.php?page=<?= $_GET['page']; ?>&ctrl=finances&action=apport">
                <input type="hidden" name="form_type" value="2">
                <div class="modal-body">
                    <label for="montant_apport_libre" class="form-label">Montant</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="montant_apport_libre" name="montant_apport_libre">
                    </div>
                    <div class="apport">
                        <label class="apport-title">Apport de :</label>
                        <?php foreach ($members as $m) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="members[]" value="<?= $m->id_user(); ?>" id="member_<?= $m->id_user(); ?>">
                                <label class="form-check-label" for="member_<?= $m->id_user(); ?>">
                                    <?= Users::getById($m->id_user())->first_name(); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <label for="description_apport_libre" class="form-label">Description (facultative)</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="description_apport_libre" name="description_apport_libre">
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

<div class="finances__header">
    <div class="balance">
        <div class="finances-title">Recettes</div>
        <div class="finances-value balance-number <?php if ($recettes_totales >= 0) : ?>good<?php else : ?>bad<?php endif; ?>"><?= number_format($recettes_totales / 100, 2, ',', ' '); ?>€</div>
    </div>
    <div class="balance">
        <div class="finances-title">Caisse</div>
        <div class="finances-value <?php if ($caisse >= 0) : ?>good<?php else : ?>bad<?php endif; ?>"><?= number_format($caisse / 100, 2, ',', ' '); ?>€</div>
    </div>
    <div class="benefits">
        <div class="finances-title">Investi</div>
        <div class="finances-value"><?= number_format($apport / 100, 2, ',', ' '); ?>€</div>
    </div>
    <div class="losses">
        <div class="finances-title">Réel gagné</div>
        <div class="finances-value"><?= number_format($reel_gagne / 100, 2, ',', ' '); ?>€</div>
    </div>
</div>

<?php if ($rank->finances() == 1) : ?>
<div class="finances__actions">
    <div class="actions">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_entree">
            Entrée d'argent
        </button>
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal_sortie">
            Sortie d'argent
        </button>
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_apport">
            Apport
        </button>
    </div>
</div>
<?php endif; ?>

<table class="table table-finance">
    <thead class="table-header">
        <tr>
            <th>Date</th>
            <th>Description</th>
            <th class="text-center">Entrée (en €)</th>
            <th class="text-center">Sortie (en €)</th>
            <th class="text-center">Solde (en €)</th>
            <th class="text-end">
                <a href="index.php?page=<?= $page->uuid(); ?>&ctrl=finances&action=export&mode=empty" class="btn btn-outline-primary">Exporter Excel</button>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if ($finances != null) : ?>
            <?php $total = 0; ?>
            <?php foreach ($finances as $f) : ?>
                <tr>
                    <td><span class="date-table"><?= date('d/m/Y', strtotime($f->date_add())); ?></span></td>
                    <td><?= $f->description(); ?></td>
                    <td class="text-center<?php if ($f->type() == 2) : ?> bg-success<?php endif; ?>"><?php if ($f->type() == 1 || $f->type() == 2) : ?><?= number_format($f->amount() / 100, 2, ',', ' '); ?><?php endif; ?></td>
                    <td class="text-center"><?php if ($f->type() == 0 || $f->type() == 2) : ?><?= number_format($f->amount() / 100, 2, ',', ' '); ?><?php endif; ?></td>
                    <?php 
                        if ($f->type() == 1)
                            $total += ($f->amount() / 100);
                        else if ($f->type() == 2)
                            $total = $total;
                        else
                            $total -= ($f->amount() / 100);
                    ?>
                    <td class="text-center"><?= number_format($total, 2, ',', ' '); ?></td>
                    <td class="text-end">
                        <?php if ($rank->finances() == 1) : ?>
                            <a data-id="<?= $f->id(); ?>" href="index.php?page=<?= $page->uuid(); ?>&ctrl=finances&action=supprimer&id=<?= $f->id(); ?>" class="delete"><span class="material-icons">delete_outline</span></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr id="delete_<?= $f->id(); ?>" class="delete-message" style="display: none">
                    <td colspan="6">
                        <div class="alert alert-info alert-justify">
                            <div class="message">
                                Êtes-vous sûr de vouloir supprimer cette <?= ($f->type() == 1) ? 'entrée' : 'sortie'; ?> d'argent ?
                            </div>
                            <div class="action-buttons">
                                <button class="btn btn-danger btn-oui" id="btn_oui_<?= $f->id(); ?>" data-link="">Oui</button>
                                <button class="btn btn-outline-primary btn-non" data-id="<?= $f->id(); ?>">Non</button>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="<?php if ($rank->finances() == 1) : ?>6<?php else : ?>5<?php endif; ?>">
                    <div class="alert alert-info">
                        <span class="material-icons">info_outline</span>
                        Aucune entrée ou sortie d'argent n'a encore été déclarée.
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>