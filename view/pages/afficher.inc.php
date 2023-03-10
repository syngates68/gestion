<!-- Modal de création de groupe -->
<div class="modal fade" id="modal_nouveau_groupe" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer un groupe</h5>
            </div>
            <form method="POST" action="index.php?ctrl=pages&action=creer">
                <div class="modal-body">
                    <label for="group_name" class="form-label">Nom du groupe</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="group_name" name="group_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary btn-creer disabled" type="submit">Créer le groupe</button>
                    <button class="btn btn-primary btn-creer-loading" type="button" style="display: none;" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Création en cours...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<h2>Vos groupes</h2>
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
<div class="groups-list">
    <?php 
    if (sizeof($pages) > 0) :
        for ($i = 0; $i < sizeof($pages); $i++) :
    ?>
            <a href="index.php?page=<?= $pages[$i]->uuid(); ?>&ctrl=finances&action=afficher">
                <div class="group-card">
                    <div class="group-img">
                        <img src="<?= $pages[$i]->picture(); ?>">
                    </div>
                    <div class="group-card__body">
                        <div class="group-title"><?= $pages[$i]->name(); ?></div>
                        <div class="group-owner">Administré par <?= Users::getById($pages[$i]->id_creator())->first_name().' '.Users::getById($pages[$i]->id_creator())->name(); ?></div>
                    </div>
                </div>
            </a>
    <?php
        endfor;
    endif; 
    ?>
    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_nouveau_groupe">
        <div class="group-card group-card-new">
            <div class="group-card__body">
                <span class="material-icons-outlined">add_box</span>
                <div class="title">Nouveau groupe</div>
            </div>
        </div>
    </a>
</div>