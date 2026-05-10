<?= view('layouts/header', ['title' => 'Connexion']) ?>

<div class="page auth-page">
    <div class="auth-layout">
        <section class="auth-hero">
            <div class="auth-kicker">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Espace sécurisé
            </div>

            <h2>Retrouvez votre programme et vos progrès en un clin d'œil.</h2>
            <p>
                Retrouvez votre programme, vos données et vos recommandations dans un espace pensé pour la lisibilité,
                la confiance et la vitesse d’usage.
            </p>

           
        </section>

        <div class="card auth-card">
            <div class="card-eyebrow">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Connexion sécurisée
            </div>

            <h2>Bon retour&nbsp;!</h2>
            <p class="card-subtitle">Connectez-vous pour accéder à votre programme.</p>

            <?php if ($success = session()->getFlashdata('success')): ?>
                <div class="alert-success visible auth-alert" role="status"><?= esc($success) ?></div>
            <?php endif; ?>

            <?php if ($error = session()->getFlashdata('error')): ?>
                <div class="alert-error visible auth-alert" role="alert" id="form-message"><?= esc($error) ?></div>
            <?php else: ?>
                <div class="auth-message" id="form-message" aria-live="polite"></div>
            <?php endif; ?>

            <form id="loginForm" class="auth-form" method="post" action="/login" novalidate>
                <?= csrf_field() ?>

                <div class="field" id="email-field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="vous@exemple.com" value="<?= esc(old('email')) ?>" autocomplete="email" required>
                    <small class="field-feedback" id="email-feedback">Saisissez une adresse e-mail valide.</small>
                </div>

                <div class="field field-password" id="password-field">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" id="password-toggle" aria-label="Afficher le mot de passe" aria-pressed="false">
                            <svg class="icon-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-hide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"></path>
                                <path d="M1 1l22 22"></path>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a21.42 21.42 0 0 1-2.68 3.76"></path>
                                <path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"></path>
                            </svg>
                        </button>
                    </div>
                    <small class="field-feedback" id="password-feedback">Ce champ est requis pour continuer.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-submit">
                        Se connecter
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>

                    <a href="/register-step1" class="btn btn-outline btn-secondary">
                        Créer un compte
                    </a>
                </div>
            </form>

         
        </div>
    </div>
</div>

<script src="assets/js/auth.js"></script>

<?= view('layouts/footer') ?>