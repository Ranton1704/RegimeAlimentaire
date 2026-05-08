<?= view('layouts/header', ['title' => ($action === 'create' ? 'Créer' : 'Éditer') . ' un code wallet']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2><?= ($action === 'create' ? 'Créer' : 'Éditer') ?> un code wallet</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $action === 'create' ? '/gestion/codes-wallet/store' : '/gestion/codes-wallet/update/' . ($code['id'] ?? '') ?>">
            <?= csrf_field() ?>

            <div class="field">
                <label>Code</label>
                <input type="text" name="code" value="<?= esc($code['code'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label>Montant</label>
                <input type="number" step="0.01" name="montant" value="<?= esc($code['montant'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description"><?= esc($code['description'] ?? '') ?></textarea>
            </div>

            <div class="field">
                <label><input type="checkbox" name="utilise" value="1" <?= !empty($code['utilise']) ? 'checked' : '' ?>> Code validé</label>
            </div>

            <div class="field">
                <label>Utilisateur bénéficiaire</label>
                <select name="used_by">
                    <option value="">-- Aucun --</option>
                    <?php foreach (($users ?? []) as $user): ?>
                        <option value="<?= esc($user['id']) ?>" <?= (string) ($code['used_by'] ?? '') === (string) $user['id'] ? 'selected' : '' ?>>
                            <?= esc($user['nom'] . ' ' . $user['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Enregistrer</button>
        </form>

        <p class="back-link"><a href="/gestion/codes-wallet">Retour</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
