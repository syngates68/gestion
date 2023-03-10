<div class="settings">
    <?php if ($rank->settings() == 1) : ?>
        <?php
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
        <form method="POST" enctype="multipart/form-data" action="index.php?page=<?= $_GET['page']; ?>&ctrl=parametres&action=modifier">
            <div class="settings__part">
                <div class="settings__part-title">Informations de la page</div>
                <div class="section">
                    <div class="group-img-section">
                        <div class="group-img-section__container">
                            <img src="<?= $p->picture(); ?>">
                        </div>
                        <div>
                            <label for="group_picture" class="form-label">Photo du groupe</label>
                            <input class="form-control" type="file" id="group_picture" name="group_picture">
                        </div>
                    </div>
                </div>
                <div class="section">
                    <label for="section-group_name" class="form-label">Nom du groupe</label>
                    <input class="form-control" type="text" id="group_name" name="group_name" value="<?= $p->name(); ?>">
                </div>
            </div>
            <div class="settings__footer">
                <button class="btn btn-primary">Modifier</button>
            </div>
        </form>
    <?php else : ?>
        <div class="alert alert-danger">
            <span class="material-icons">cancel</span>
            Vous n'avez pas les autorisations nécessaires pour accéder aux paramètres de cette page. Vous pouvez demander ces droits à
            l'administrateur de cette dernière.
        </div>
    <?php endif; ?>
</div>