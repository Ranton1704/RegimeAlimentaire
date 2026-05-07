<?= view('layouts/header', ['title' => 'Wallet']) ?>

<div class="page page-wallet">
    <div class="card wallet-card">
        <div class="card-eyebrow">Porte-monnaie</div>
        <h2>Votre solde</h2>
        <p class="card-subtitle">Rechargez avec un code et consultez votre historique.</p>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert-error visible"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="wallet-balance">
            <span>Solde actuel</span>
            <strong><?= esc(number_format((float) ($user['solde'] ?? 0), 2, ',', ' ')) ?> Ar</strong>
        </div>

        <div class="wallet-badge <?= !empty($user['is_gold']) ? 'gold' : '' ?>">
            <?= !empty($user['is_gold']) ? 'Compte Gold actif' : 'Compte Standard' ?>
        </div>

        <form method="post" action="/wallet/recharge" class="wallet-form">
            <?= csrf_field() ?>
            <div class="field">
                <label for="code">Code de recharge</label>
                <input type="text" id="code" name="code" placeholder="XXXXX-XXXXX" required>
            </div>
            <button type="submit" class="btn btn-primary">Recharger</button>
        </form>

        <div class="wallet-actions">
            <a class="btn btn-outline" href="/buy-gold">Acheter Gold</a>
            <a class="btn btn-outline" href="/dashboard">Retour dashboard</a>
        </div>
    </div>

    <div class="card wallet-history">
        <h3>Historique des opérations</h3>
        <?php if (!empty($transactions)) : ?>
            <div class="transaction-list">
                <?php foreach ($transactions as $tx) : ?>
                    <div class="transaction-item">
                        <div>
                            <strong><?= esc($tx['type']) ?></strong>
                            <p><?= esc($tx['description'] ?? '') ?></p>
                        </div>
                        <div class="transaction-amount">+<?= esc(number_format((float) $tx['montant'], 2, ',', ' ')) ?> Ar</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Aucune transaction enregistrée.</p>
        <?php endif; ?>
    </div>
</div>

<?= view('layouts/footer') ?>
