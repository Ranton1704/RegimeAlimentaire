<?= view('layouts/header', ['title' => 'Gestion des paramètres']) ?>

<div class="page">
    <div class="card">
        <h2>Paramètres</h2>
        <p><a href="/gestion/parametres/create" class="btn">Créer un paramètre</a></p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Clé</th>
                    <th>Valeur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parametres as $parametre): ?>
                    <tr>
                        <td><?= esc($parametre['cle']) ?></td>
                        <td><?= esc($parametre['valeur']) ?></td>
                        <td>
                            <a href="/gestion/parametres/edit/<?= $parametre['id'] ?>">Éditer</a> |
                            <a href="/gestion/parametres/delete/<?= $parametre['id'] ?>" onclick="return confirm('Supprimer ce paramètre ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('layouts/footer') ?>
