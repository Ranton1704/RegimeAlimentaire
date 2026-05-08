<?= view('layouts/header', ['title' => 'Gestion des paramètres']) ?>

<div class="container admin-page">
    <div class="card admin-card">
        <h2>Paramètres</h2>
        <p><a href="/gestion/parametres/create" class="btn">Créer un paramètre</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="stats-table-wrap">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Clé</th>
                        <th>Valeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($parametres)): ?>
                        <?php foreach ($parametres as $parametre): ?>
                            <tr>
                                <td data-label="Clé"><?= esc($parametre['cle']) ?></td>
                                <td data-label="Valeur"><?= esc($parametre['valeur']) ?></td>
                                <td data-label="Actions">
                                    <span class="action-links">
                                        <a href="/gestion/parametres/edit/<?= (int) $parametre['id'] ?>">Éditer</a>
                                        <a href="/gestion/parametres/delete/<?= (int) $parametre['id'] ?>" onclick="return confirm('Supprimer ce paramètre ?')">Supprimer</a>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Aucun paramètre</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
