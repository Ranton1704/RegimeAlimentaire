<?= view('layouts/header', ['title' => ($action === 'create' ? 'Créer' : 'Éditer') . ' un paramètre']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2><?= ($action === 'create' ? 'Créer' : 'Éditer') ?> un paramètre</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $action === 'create' ? '/gestion/parametres/store' : '/gestion/parametres/update/' . ($parametre['id'] ?? '') ?>">
            <?= csrf_field() ?>

            <div class="field">
                <label>Clé</label>
                <input type="text" name="cle" value="<?= esc($parametre['cle'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label>Valeur</label>
                <input type="text" name="valeur" value="<?= esc($parametre['valeur'] ?? '') ?>" required>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Enregistrer</button>
        </form>

        <p class="back-link"><a href="/gestion/parametres">Retour</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
