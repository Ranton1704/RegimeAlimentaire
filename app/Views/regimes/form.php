<?= view('layouts/header', ['title' => ($action === 'create' ? 'Créer' : 'Éditer') . ' un régime']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2><?= ($action === 'create' ? 'Créer' : 'Éditer') ?> un régime</h2>

        <form method="post" action="<?= $action === 'create' ? '/gestion/regimes/store' : '/gestion/regimes/update/' . ($regime['id'] ?? '') ?>">
            <?= csrf_field() ?>

            <div class="field">
                <label>Nom</label>
                <input type="text" name="nom" value="<?= esc($regime['nom'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description"><?= esc($regime['description'] ?? '') ?></textarea>
            </div>

            <div class="field">
                <label>Objectif</label>
                <select name="objectifs_id" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach (($objectifs ?? []) as $objectif): ?>
                        <option value="<?= esc($objectif['id']) ?>" <?= (string) ($regime['objectifs_id'] ?? '') === (string) $objectif['id'] ? 'selected' : '' ?>>
                            <?= esc($objectif['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Variation poids (kg)</label>
                <input type="number" step="0.1" name="variation_poids" value="<?= esc($regime['variation_poids'] ?? '0') ?>">
            </div>

            <div class="field">
                <label>% Viande</label>
                <input type="number" name="pourcentage_viande" value="<?= esc($regime['pourcentage_viande'] ?? '') ?>">
            </div>
            <div class="field">
                <label>% Poisson</label>
                <input type="number" name="pourcentage_poisson" value="<?= esc($regime['pourcentage_poisson'] ?? '') ?>">
            </div>
            <div class="field">
                <label>% Volaille</label>
                <input type="number" name="pourcentage_volaille" value="<?= esc($regime['pourcentage_volaille'] ?? '') ?>">
            </div>

            <div class="field">
                <label>Prix (Ar)</label>
                <input type="number" step="0.01" name="prix" value="">
            </div>
            <div class="field">
                <label>Durée (jours)</label>
                <input type="number" name="duree_jours" value="30">
            </div>

            <?php if (!empty($prices) && $action === 'edit'): ?>
                <div class="field">
                    <label>Prix existants</label>
                    <ul>
                        <?php foreach ($prices as $price): ?>
                            <li><?= esc(number_format((float) $price['prix'], 2, ',', ' ')) ?> Ar — <?= esc((string) $price['duree_jours']) ?> jours</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <button class="btn btn-primary btn-block" type="submit"><?= ($action === 'create' ? 'Créer' : 'Éditer') ?></button>
        </form>

        <p class="back-link"><a href="/gestion/regimes">Retour</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
