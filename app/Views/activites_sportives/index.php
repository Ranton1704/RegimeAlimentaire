<?= view('layouts/header', ['title' => 'Gestion des activités sportives']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2>Activités sportives</h2>
        <p><a href="/gestion/activites-sportives/create" class="btn">Créer une activité</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Objectif</th>
                    <th>Durée</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activites as $a): ?>
                    <tr>
                        <td data-label="Nom"><?= esc($a['nom']) ?></td>
                        <td data-label="Objectif"><?= esc($a['objectif_nom'] ?? ('#' . $a['objectifs_id'])) ?></td>
                        <td data-label="Durée"><?= esc((string) $a['duree_minutes']) ?> min</td>
                        <td data-label="Description"><?= esc($a['description']) ?></td>
                        <td data-label="Actions">
                            <span class="action-links">
                                <a href="/gestion/activites-sportives/show/<?= $a['id'] ?>">Voir</a>
                                <a href="/gestion/activites-sportives/edit/<?= $a['id'] ?>">Éditer</a>
                                <a href="/gestion/activites-sportives/delete/<?= $a['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('layouts/footer') ?>
