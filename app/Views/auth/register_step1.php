<?= view('layouts/header', ['title' => 'Inscription — Étape 1']) ?>

<div class="page">
    <div class="card">

        <div class="steps">
            <div class="step active">
                <div class="dot">1</div>
                <span>Profil</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="dot">2</div>
                <span>Santé</span>
            </div>
        </div>

        <h2>Créez votre compte</h2>
        <p class="card-subtitle">Quelques informations pour personnaliser votre programme.</p>

        <div class="alert-error" id="error"><?= session()->getFlashdata('error') ?? '' ?></div>

        <form id="step1Form" method="post" action="/register-step1">
            <?= csrf_field() ?>

            <fieldset>
                <legend>
                    <span class="legend-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    Informations personnelles
                </legend>

                <div class="field">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Dupont" required>
                </div>

                <div class="field">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Jean" required>
                </div>

                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="vous@exemple.com" required>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="field">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" required>
                            <option value="">Choisir</option>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary">
                Étape suivante
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
        </form>

        <div class="card-footer">
            Déjà inscrit ? <a href="/login">Se connecter</a>
        </div>

    </div>
</div>

<script src="assets/js/register.js"></script>

<?= view('layouts/footer') ?>