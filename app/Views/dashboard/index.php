<?= view('layouts/header', ['title' => 'Dashboard']) ?>

<div class="container">

    <div class="dashboard-grid">
        <div class="dashboard-main">
            <?php if (!empty($profil)) : ?>
                <div class="hero-banner card">
                    <div>
                        <div class="card-eyebrow">Programme personnalisé</div>
                        <h2>Bonjour <?= esc($user['prenom'] ?? 'utilisateur') ?> 👋</h2>
                        <p class="card-subtitle">Votre profil, vos objectifs et vos suggestions sont regroupés ici.</p>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span>Poids cible</span>
                            <strong><?= esc((string) (session()->get('onboarding_health')['poids_souhaite'] ?? $profil['poids'])) ?> kg</strong>
                        </div>
                        <div class="hero-stat">
                            <span>Variation estimée</span>
                            <strong><?= esc(number_format((float) ($estimatedChange ?? 0), 2, ',', ' ')) ?> kg</strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="cards-row">
                <div class="card small">
                    <h3>IMC</h3>
                    <?php if (!empty($profil)) : ?>
                        <p class="big"><?= esc((string) $profil['imc']) ?></p>
                        <p><?= esc($imcCategory) ?></p>
                    <?php else : ?>
                        <p>Aucune donnée santé</p>
                    <?php endif; ?>
                </div>

                <div class="card small">
                    <h3>Objectifs</h3>
                    <?php if (!empty($objectifs)) : ?>
                        <ul>
                            <?php foreach ($objectifs as $o) : ?>
                                <li><?= esc($o['nom']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><a href="/objectifs">Choisir mes objectifs</a></p>
                    <?php endif; ?>
                </div>

                <div class="card small">
                    <h3>Wallet</h3>
                    <p class="big"><?= isset($user['solde']) ? esc((string) $user['solde']) . ' Ar' : '0.00 Ar' ?></p>
                    <p><a href="/wallet">Voir</a></p>
                </div>

                <div class="card small">
                    <h3>Mon profil</h3>
                    <p>Gérer mes informations</p>
                    <p><a href="/profil">Modifier</a></p>
                </div>
            </div>

            <div class="card">
                <h3>Recommandations</h3>
                <p>Basées sur vos objectifs et IMC</p>

                <?php if (!empty($recommendations)) : ?>
                    <div class="recommendations">
                        <?php foreach ($recommendations as $item) :
                            $regime = $item['regime']; ?>
                            <div class="regime">
                                <h4><?= esc($regime['nom']) ?></h4>
                                <p><?= esc($regime['description']) ?></p>
                                <div class="regime-meta">
                                    <span>Variation: <?= esc((string) $regime['variation_poids']) ?></span>
                                    <?php if (!empty($item['prix'])): ?>
                                        <?php if (!empty($isGold) && !empty($item['discount_rate'])): ?>
                                            <span> — Prix: <s><?= esc(number_format((float) $item['prix_base'], 2, ',', ' ')) ?> Ar</s> <?= esc(number_format((float) $item['prix'], 2, ',', ' ')) ?> Ar</span>
                                            <span> — Remise Gold: -15%</span>
                                        <?php else: ?>
                                            <span> — Prix: <?= esc(number_format((float) $item['prix'], 2, ',', ' ')) ?> Ar</span>
                                        <?php endif; ?>
                                        <span> — Durée: <?= esc((string) $item['duree_jours']) ?> jours</span>
                                    <?php endif; ?>
                                </div>
                                <div style="margin-top:8px;">
                                    <a class="btn" href="/regime/<?= (int) $regime['id'] ?>/buy">Acheter</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>Aucune recommandation pour le moment.</p>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Activités sportives conseillées</h3>
                <p>Activités liées à vos objectifs sélectionnés</p>

                <?php if (!empty($activities)) : ?>
                    <div class="recommendations">
                        <?php foreach ($activities as $activity) : ?>
                            <div class="regime activity-card">
                                <h4><?= esc($activity['nom']) ?></h4>
                                <p><?= esc($activity['description']) ?></p>
                                <div class="regime-meta">
                                    <span>Durée: <?= esc((string) ($activity['duree_minutes'] ?? 0)) ?> min</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>Aucune activité disponible pour le moment.</p>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Statistiques</h3>
                <p>Graphes et tableau de suivi de vos actions</p>

                <div class="stats-grid">
                    <div class="stat-kpi">
                        <span>Régimes actifs</span>
                        <strong><?= esc((string) ($activeRegimes ?? 0)) ?></strong>
                    </div>
                    <div class="stat-kpi">
                        <span>Total recharges</span>
                        <strong><?= esc(number_format((float) ($walletStats['recharge'] ?? 0), 2, ',', ' ')) ?> Ar</strong>
                    </div>
                    <div class="stat-kpi">
                        <span>Total dépenses</span>
                        <strong><?= esc(number_format((float) (($walletStats['achat_regime'] ?? 0) + ($walletStats['achat_gold'] ?? 0)), 2, ',', ' ')) ?> Ar</strong>
                    </div>
                </div>

                <div class="stats-bars">
                    <div class="stats-bar-row">
                        <span>Régimes</span>
                        <div class="stats-bar-track"><div class="stats-bar-fill" style="width: <?= esc((string) ($walletBars['regimes'] ?? 0)) ?>%"></div></div>
                        <strong><?= esc(number_format((float) ($walletStats['achat_regime'] ?? 0), 2, ',', ' ')) ?> Ar</strong>
                    </div>
                    <div class="stats-bar-row">
                        <span>Gold</span>
                        <div class="stats-bar-track"><div class="stats-bar-fill" style="width: <?= esc((string) ($walletBars['gold'] ?? 0)) ?>%"></div></div>
                        <strong><?= esc(number_format((float) ($walletStats['achat_gold'] ?? 0), 2, ',', ' ')) ?> Ar</strong>
                    </div>
                </div>

                <div class="stats-table-wrap">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Régime</th>
                                <th>Durée (jours)</th>
                                <th>Prix (Ar)</th>
                                <th>Variation (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($statsTable)) : ?>
                                <?php foreach ($statsTable as $row) : ?>
                                    <tr>
                                        <td data-label="Régime"><?= esc($row['regime']) ?></td>
                                        <td data-label="Durée (jours)"><?= esc((string) $row['duree']) ?></td>
                                        <td data-label="Prix (Ar)"><?= esc(number_format((float) $row['prix'], 2, ',', ' ')) ?></td>
                                        <td data-label="Variation (kg)"><?= esc(number_format((float) $row['variation'], 2, ',', ' ')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4">Aucune donnée statistique disponible.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3>Estimation d'évolution du poids</h3>
                <p>Simulation basée sur les régimes recommandés</p>

                <?php if (!empty($weightForecast)) : ?>
                    <div class="recommendations">
                        <?php foreach ($weightForecast as $forecast) : ?>
                            <div class="regime forecast-card">
                                <h4><?= esc($forecast['regime']['nom']) ?></h4>
                                <p>
                                    Sur <?= esc((string) $forecast['jours']) ?> jours, variation estimée :
                                    <strong><?= esc(number_format((float) $forecast['delta'], 2, ',', ' ')) ?> kg</strong>
                                </p>
                                <p>Poids final estimé : <strong><?= esc(number_format((float) $forecast['poids_final'], 2, ',', ' ')) ?> kg</strong></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>Aucune estimation disponible.</p>
                <?php endif; ?>
            </div>
        </div>

        <aside class="dashboard-aside">
            <div class="card">
                <h4>Actions rapides</h4>
                <div class="quick-actions">
                    <a class="quick-action" href="/export/pdf">
                        <span class="qa-icon">PDF</span>
                        <span>
                            <strong>Exporter</strong>
                            <small>Profil complet</small>
                        </span>
                    </a>
                    <a class="quick-action" href="/buy-gold">
                        <span class="qa-icon">★</span>
                        <span>
                            <strong>Gold</strong>
                            <small>Débloquer -15%</small>
                        </span>
                    </a>
                    <a class="quick-action" href="/wallet">
                        <span class="qa-icon">+</span>
                        <span>
                            <strong>Recharge</strong>
                            <small>Ajouter du solde</small>
                        </span>
                    </a>
                    <a class="quick-action danger" href="/logout">
                        <span class="qa-icon">↩</span>
                        <span>
                            <strong>Quitter</strong>
                            <small>Déconnexion</small>
                        </span>
                    </a>
                </div>
            </div>
        </aside>
    </div>

</div>

<?= view('layouts/footer') ?>