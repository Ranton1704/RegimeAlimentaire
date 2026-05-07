<?= view('layouts/header', ['title' => 'Détail du régime']) ?>

<div class="page">
    <div class="card">
        <h2><?= esc($regime['nom']) ?></h2>
        <p class="card-subtitle"><?= esc($regime['description']) ?></p>

        <div class="cards-row">
            <div class="card small"><h3>Objectif</h3><p><?= esc($regime['objectif_nom'] ?? '—') ?></p></div>
            <div class="card small"><h3>Variation</h3><p><?= esc((string) $regime['variation_poids']) ?> kg</p></div>
            <div class="card small"><h3>Répartition</h3><p>V: <?= esc((string) $regime['pourcentage_viande']) ?>% | P: <?= esc((string) $regime['pourcentage_poisson']) ?>% | W: <?= esc((string) $regime['pourcentage_volaille']) ?>%</p></div>
        </div>

        <h3>Prix selon la durée</h3>
        <?php if (!empty($prices)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Durée (jours)</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prices as $price): ?>
                        <tr>
                            <td><?= esc((string) $price['duree_jours']) ?></td>
                            <td><?= esc(number_format((float) $price['prix'], 2, ',', ' ')) ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun prix enregistré.</p>
        <?php endif; ?>

        <p style="margin-top:16px;"><a href="/gestion/regimes">Retour à la liste</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
