<?= view('layouts/header', ['title' => 'Inscription — Étape 2']) ?>

<div class="page">
    <div class="card">

        <div class="steps">
            <div class="step done">
                <div class="dot">✓</div>
                <span>Profil</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="dot">2</div>
                <span>Santé</span>
            </div>
        </div>

        <h2>Vos données de santé</h2>
        <p class="card-subtitle">Utilisées pour calculer votre programme nutritionnel personnalisé.</p>

        <div class="alert-error" id="error"><?= session()->getFlashdata('error') ?? '' ?></div>

        <form method="post" action="/register-step2">
            <?= csrf_field() ?>

            <fieldset>
                <legend>
                    <span class="legend-icon">
                        <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </span>
                    Informations de santé
                </legend>

                <div class="field-row">
                    <div class="field">
                        <label for="taille">Taille (m)</label>
                        <input type="number" id="taille" step="0.01" name="taille" placeholder="1.75" required>
                    </div>
                    <div class="field">
                        <label for="poids">Poids actuel (kg)</label>
                        <input type="number" id="poids" step="0.1" name="poids" placeholder="70" required>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="poids_souhaite">Poids souhaité (kg)</label>
                        <input type="number" id="poids_souhaite" step="0.1" name="poids_souhaite" placeholder="65" required>
                    </div>
                    <div class="field">
                        <label for="activite">Niveau d'activité physique</label>
                        <select id="activite" name="activite" required>
                            <option value="">Choisir</option>
                            <option value="Faible">Faible</option>
                            <option value="Moderee">Modérée</option>
                            <option value="Intense">Intense</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary">
                Terminer l'inscription
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </button>
        </form>

    </div>
</div>

<?= view('layouts/footer') ?>