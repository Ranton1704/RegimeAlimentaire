<?= view('layouts/header', ['title' => 'Choix des objectifs']) ?>

<div class="page">
    <div class="card">
        <h2>Choisissez vos objectifs</h2>
        <p class="card-subtitle">Sélectionnez de 1 à 3 objectifs pour personnaliser votre programme.</p>

        <div class="alert-error" id="error"><?= session()->getFlashdata('error') ?? '' ?></div>

        <form method="post" action="/objectifs">
            <?= csrf_field() ?>

            <?php if (!empty($objectifs)) : ?>
                <?php foreach ($objectifs as $objectif) : ?>
                    <div class="field" style="margin-bottom: 10px;">
                        <label>
                            <input
                                type="checkbox"
                                name="objectifs[]"
                                value="<?= esc($objectif['id']) ?>"
                                <?= in_array((int) $objectif['id'], $selectedIds ?? [], true) ? 'checked' : '' ?>
                            >
                            <strong><?= esc($objectif['nom']) ?></strong>
                            <br>
                            <small><?= esc($objectif['description']) ?></small>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun objectif disponible pour le moment.</p>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Enregistrer mes objectifs</button>
        </form>
    </div>
</div>

<?= view('layouts/footer') ?>
