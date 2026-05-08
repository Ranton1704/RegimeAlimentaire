<?= view('layouts/header', ['title' => 'Gestion des régimes']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2>Régimes</h2>
        <p><a href="/gestion/regimes/create" class="btn">Créer un régime</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Objectif</th>
                    <th>Variation (kg)</th>
                    <th>% Viande</th>
                    <th>% Poisson</th>
                    <th>% Volaille</th>
                    <th>Prix / durée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimes as $r): ?>
                    <tr>
                        <td data-label="Nom"><?= esc($r['nom']) ?></td>
                        <td data-label="Objectif"><?= esc($r['objectif_nom'] ?? ('#' . $r['objectifs_id'])) ?></td>
                        <td data-label="Variation (kg)"><?= esc($r['variation_poids']) ?></td>
                        <td data-label="% Viande"><?= esc($r['pourcentage_viande']) ?>%</td>
                        <td data-label="% Poisson"><?= esc($r['pourcentage_poisson']) ?>%</td>
                        <td data-label="% Volaille"><?= esc($r['pourcentage_volaille']) ?>%</td>
                        <td data-label="Prix / durée">
                            <?php if (!empty($pricesByRegime[(int) $r['id']])): ?>
                                <?php foreach ($pricesByRegime[(int) $r['id']] as $price): ?>
                                    <div><?= esc(number_format((float) $price['prix'], 2, ',', ' ')) ?> Ar / <?= esc((string) $price['duree_jours']) ?> j</div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td data-label="Actions">
                            <span class="action-links">
                                <a href="/gestion/regimes/show/<?= $r['id'] ?>">Voir</a>
                                <a href="/gestion/regimes/edit/<?= $r['id'] ?>">Éditer</a>
                                <a href="/gestion/regimes/delete/<?= $r['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('layouts/footer') ?>
