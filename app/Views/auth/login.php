<?= view('layouts/header', ['title' => 'Connexion']) ?>

<div class="page">
    <div class="card">

        <div class="card-eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Espace sécurisé
        </div>

        <h2>Bon retour&nbsp;!</h2>
        <p class="card-subtitle">Connectez-vous pour accéder à votre programme.</p>

        <div class="alert-error" id="error"><?= session()->getFlashdata('error') ?? '' ?></div>

        <form id="loginForm" method="post" action="/login">
            <?= csrf_field() ?>

            <div class="field">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" placeholder="vous@exemple.com" required>
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Se connecter
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
        </form>

        <div class="card-footer">
            Pas encore de compte ? <a href="/register-step1">Créer un compte</a>
        </div>

    </div>
</div>

<script src="assets/js/auth.js"></script>

<?= view('layouts/footer') ?>