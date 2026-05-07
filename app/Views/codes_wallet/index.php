<?= view('layouts/header', ['title' => 'Gestion des codes wallet']) ?>

<div class="page">
    <div class="card">
        <h2>Codes wallet</h2>
        <p><a href="/gestion/codes-wallet/create" class="btn">Créer un code</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Montant</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Utilisé par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $code): ?>
                    <tr>
                        <td><?= esc($code['code']) ?></td>
                        <td><?= esc(number_format((float) $code['montant'], 2, ',', ' ')) ?> Ar</td>
                        <td><?= esc($code['description'] ?? '') ?></td>
                        <td><?= !empty($code['utilise']) ? 'Utilisé' : 'Disponible' ?></td>
                        <td><?= esc($code['used_by_name'] ?? '—') ?></td>
                        <td><?= esc($code['utilise_le'] ?? '—') ?></td>
                        <td>
                            <a href="/gestion/codes-wallet/edit/<?= $code['id'] ?>">Éditer / Valider</a> |
                            <a href="/gestion/codes-wallet/delete/<?= $code['id'] ?>" onclick="return confirm('Supprimer ce code ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('layouts/footer') ?>
