<?= view('layouts/header', ['title' => 'Inscription — Étape 1']) ?>

<div class="page auth-page page-register">
    <div class="auth-layout">
        <section class="auth-hero">
            <div class="auth-kicker">Inscription</div>
            <h2>Créez votre compte</h2>
            <p>Quelques informations pour personnaliser votre programme.</p>
        </section>

        <div class="card auth-card">

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

                <div class="field" id="nom-field">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Dupont" value="<?= esc(old('nom')) ?>" required>
                    <small class="field-feedback" id="nom-feedback">Indiquez votre nom de famille.</small>
                </div>

                <div class="field" id="prenom-field">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Jean" value="<?= esc(old('prenom')) ?>" required>
                    <small class="field-feedback" id="prenom-feedback">Indiquez votre prénom.</small>
                </div>

                <div class="field" id="email-field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="vous@exemple.com" value="<?= esc(old('email')) ?>" required>
                    <small class="field-feedback" id="email-feedback">Saisissez une adresse e-mail valide.</small>
                </div>

                <div class="field-row">
                    <div class="field password-field-shell" id="password-field">
                        <label for="password">Mot de passe</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" id="passwordToggle" class="password-toggle" aria-label="Afficher le mot de passe" aria-pressed="false">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                        <small class="field-feedback" id="password-feedback">Au moins 6 caractères.</small>
                    </div>
                    <div class="field" id="genre-field">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" required>
                            <option value="">Choisir</option>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                        <small class="field-feedback" id="genre-feedback">Choisissez votre genre.</small>
                    </div>
                </div>

                <div class="field" id="date-field">
                    <label for="date_naissance">Date de naissance</label>
                    <div class="date-wrap">
                        <input type="date" id="date_naissance" name="date_naissance" value="<?= esc(old('date_naissance')) ?>" required>
                      
                    </div>
                    <small class="field-feedback" id="date-feedback">Sélectionnez votre date de naissance.</small>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Étape suivante
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </div>
        </form>

        <div class="card-footer">
            Déjà inscrit ? <a href="/login">Se connecter</a>
        </div>

    </div>
</div>
</div>

<script src="assets/js/register.js"></script>

<?= view('layouts/footer') ?>