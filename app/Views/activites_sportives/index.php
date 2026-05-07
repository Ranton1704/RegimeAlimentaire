<?= view('layouts/header', ['title' => 'Gestion des activités sportives']) ?>

<div class="page">
    <div class="card">
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
                        <td><?= esc($a['nom']) ?></td>
                        <td><?= esc($a['objectif_nom'] ?? ('#' . $a['objectifs_id'])) ?></td>
                        <td><?= esc((string) $a['duree_minutes']) ?> min</td>
                        <td><?= esc($a['description']) ?></td>
                        <td>
                            <a href="/gestion/activites-sportives/show/<?= $a['id'] ?>">Voir</a> |
                            <a href="/gestion/activites-sportives/edit/<?= $a['id'] ?>">Éditer</a> |
                            <a href="/gestion/activites-sportives/delete/<?= $a['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('layouts/footer') ?>
