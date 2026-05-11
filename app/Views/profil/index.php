<?= view('layouts/header', ['title' => 'Mon profil']) ?>

<div class="dashboard-page">
    <div class="dashboard-grid">
        <div class="dashboard-main">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success visible" style="margin-bottom: 20px;">
                    <strong>✓</strong> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($user) && !empty($profil)): ?>
                <!-- HERO SECTION -->
                <div class="hero-banner">
                    <div>
                        <div class="card-eyebrow">👤 Profil Personnel</div>
                        <h2><?= esc($user['prenom']) ?> <?= esc($user['nom']) ?></h2>
                        <p class="card-subtitle">Consultez et mettez à jour vos informations personnelles et de santé.</p>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span>IMC</span>
                            <strong><?= esc((string) $profil['imc']) ?></strong>
                        </div>
                        <div class="hero-stat">
                            <span>Poids actuel</span>
                            <strong><?= esc((string) $profil['poids']) ?> kg</strong>
                        </div>
                    </div>
                </div>

                <!-- INFORMATIONS PERSONNELLES -->
                <div class="card">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 24px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <h3>Informations personnelles</h3>
                    </div>

                    <div class="cards-row">
                        <div class="profile-info-card">
                            <label>Prénom</label>
                            <p class="info-value"><?= esc($user['prenom']) ?></p>
                        </div>
                        <div class="profile-info-card">
                            <label>Nom</label>
                            <p class="info-value"><?= esc($user['nom']) ?></p>
                        </div>
                        <div class="profile-info-card">
                            <label>Email</label>
                            <p class="info-value"><?= esc($user['email']) ?></p>
                        </div>
                        <div class="profile-info-card">
                            <label>Genre</label>
                            <p class="info-value"><?= esc($user['genre'] ?? '—') ?></p>
                        </div>
                    </div>
                </div>

                <!-- DONNÉES DE SANTÉ -->
                <div class="card">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 24px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                        <h3>Données de santé</h3>
                    </div>

                    <div class="cards-row">
                        <div class="profile-info-card highlight">
                            <label>Taille</label>
                            <p class="info-value"><?= esc((string) $profil['taille']) ?> m</p>
                        </div>
                        <div class="profile-info-card highlight">
                            <label>Poids actuel</label>
                            <p class="info-value"><?= esc((string) $profil['poids']) ?> kg</p>
                        </div>
                        <div class="profile-info-card highlight">
                            <label>Poids cible</label>
                            <p class="info-value"><?= esc((string) ($profil['poids_souhaite'] ?? $profil['poids'])) ?> kg</p>
                        </div>
                        <div class="profile-info-card highlight">
                            <label>Activité physique</label>
                            <p class="info-value"><?= esc($profil['activite'] ?? 'Non définie') ?></p>
                        </div>
                    </div>

                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(224, 216, 200, 0.6); display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div class="profile-info-card stat-card">
                            <label>Indice de Masse Corporelle</label>
                            <p class="info-value big" style="background: linear-gradient(135deg, #1e7d44 0%, #2da05c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= esc((string) $profil['imc']) ?></p>
                            <p class="info-meta">
                                <?php 
                                    $imc = (float) $profil['imc'];
                                    if ($imc < 18.5) {
                                        echo '📉 Insuffisant';
                                    } elseif ($imc < 25) {
                                        echo '✅ Normal';
                                    } elseif ($imc < 30) {
                                        echo '⚠️ Surpoids';
                                    } else {
                                        echo '🚨 Obésité';
                                    }
                                ?>
                            </p>
                        </div>
                        <?php
                            $poidsActuel = (float) ($profil['poids'] ?? 0);
                            $poidsSouhaite = (float) ($profil['poids_souhaite'] ?? $poidsActuel);
                            $diff = $poidsSouhaite - $poidsActuel;
                        ?>

                        <div class="profile-info-card stat-card">
                            <label>Progression cible</label>

                            <p class="info-value big" style="background: linear-gradient(135deg, #1e7d44 0%, #2da05c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                <?= esc((string) $diff) ?> kg
                            </p>

                            <p class="info-meta">
                                <?php
                                    if ($diff < 0) {
                                        echo '📉 À perdre';
                                    } elseif ($diff > 0) {
                                        echo '📈 À gagner';
                                    } else {
                                        echo '✅ Objectif atteint';
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 28px;">
                    <a href="/profil/edit" class="btn btn-primary" style="text-align: center; width: 100%;">
                        <span style="margin-right: 8px;">✏️</span> Modifier mes informations
                    </a>
                    <a href="/dashboard" class="btn btn-outline" style="text-align: center; width: 100%;">
                        <span style="margin-right: 8px;">⬅️</span> Retour au dashboard
                    </a>
                </div>

            <?php else: ?>
                <div class="card" style="text-align: center; padding: 40px;">
                    <p>Erreur: Impossible de charger vos données.</p>
                    <a href="/dashboard" class="btn btn-primary" style="margin-top: 20px;">Retour au dashboard</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
