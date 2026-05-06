<?= view('layouts/header', ['title' => 'Dashboard']) ?>

<div class="container">

    <div class="card">
        <h2>Bienvenue 👋</h2>

        <p>
            Bonjour utilisateur,
        </p>

        <p>
            Ceci est ton espace personnel.
        </p>

        <hr>

        <ul>
            <li>✔ Profil utilisateur</li>
            <li>✔ IMC (à venir)</li>
            <li>✔ Objectifs santé</li>
            <li>✔ Régimes personnalisés</li>
            <li>✔ Wallet</li>
        </ul>

        <button onclick="window.location.href='/logout'">
            Déconnexion
        </button>
    </div>

</div>

<?= view('layouts/footer') ?>