<?= view('layouts/header', ['title' => ($action === 'create' ? 'Créer' : 'Éditer') . ' une activité']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2><?= ($action === 'create' ? 'Créer' : 'Éditer') ?> une activité sportive</h2>

        <form method="post" action="<?= $action === 'create' ? '/gestion/activites-sportives/store' : '/gestion/activites-sportives/update/' . ($activite['id'] ?? '') ?>">
            <?= csrf_field() ?>

            <div class="field">
                <label>Nom</label>
                <input type="text" name="nom" value="<?= esc($activite['nom'] ?? '') ?>" required>
            </div>

            <div class="field">
                <label>Objectif</label>
                <select name="objectifs_id" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach (($objectifs ?? []) as $objectif): ?>
                        <option value="<?= esc($objectif['id']) ?>" <?= (string) ($activite['objectifs_id'] ?? '') === (string) $objectif['id'] ? 'selected' : '' ?>>
                            <?= esc($objectif['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Durée (minutes)</label>
                <input type="number" name="duree_minutes" value="<?= esc($activite['duree_minutes'] ?? '30') ?>" required>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description"><?= esc($activite['description'] ?? '') ?></textarea>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Enregistrer</button>
        </form>

        <p class="back-link"><a href="/gestion/activites-sportives">Retour</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
