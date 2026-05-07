<?= view('layouts/header', ['title' => 'Détail activité sportive']) ?>

<div class="page">
    <div class="card">
        <h2><?= esc($activite['nom']) ?></h2>
        <p class="card-subtitle"><?= esc($activite['description']) ?></p>

        <div class="cards-row">
            <div class="card small"><h3>Objectif</h3><p><?= esc($activite['objectif_nom'] ?? '—') ?></p></div>
            <div class="card small"><h3>Durée</h3><p><?= esc((string) $activite['duree_minutes']) ?> min</p></div>
        </div>

        <p><a href="/gestion/activites-sportives">Retour à la liste</a></p>
    </div>
</div>

<?= view('layouts/footer') ?>
