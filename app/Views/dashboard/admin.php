<?= view('layouts/header', ['title' => 'Dashboard Administrateur']) ?>

<div class="container admin-dashboard">
    <div class="dashboard-grid">
        <div class="dashboard-main">
            <div class="hero-banner card">
                <div>
                    <div class="card-eyebrow">Administration</div>
                    <h2>Dashboard Administrateur</h2>
                    <p class="card-subtitle">Bienvenue <?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>.</p>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span>Utilisateurs</span>
                        <strong><?= (int) ($stats['total_users'] ?? 0) ?></strong>
                    </div>
                    <div class="hero-stat">
                        <span>Revenu total</span>
                        <strong><?= number_format((float) ($stats['revenu_total'] ?? 0), 2, ',', ' ') ?> Ar</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Indicateurs clés</h3>
                <div class="admin-kpis">
                    <div class="hero-stat">
                        <span>Utilisateurs total</span>
                        <strong><?= (int) ($stats['total_users'] ?? 0) ?></strong>
                    </div>
                    <div class="hero-stat">
                        <span>Utilisateurs Gold</span>
                        <strong><?= (int) ($stats['gold_users'] ?? 0) ?></strong>
                    </div>
                    <div class="hero-stat">
                        <span>Régimes achetés</span>
                        <strong><?= (int) ($stats['regimes_achetes'] ?? 0) ?></strong>
                    </div>
                    <div class="hero-stat">
                        <span>Codes utilisés</span>
                        <strong><?= (int) ($stats['codes_utilises'] ?? 0) ?></strong>
                    </div>
                    <div class="hero-stat">
                        <span>Revenu régimes</span>
                        <strong><?= number_format((float) ($stats['revenu_regimes'] ?? 0), 2, ',', ' ') ?> Ar</strong>
                    </div>
                    <div class="hero-stat">
                        <span>Revenu total</span>
                        <strong><?= number_format((float) ($stats['revenu_total'] ?? 0), 2, ',', ' ') ?> Ar</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Visualisations</h3>
                <div class="admin-charts-grid">
                    <div class="admin-chart-item">
                        <canvas id="chartRegistrations" height="160"></canvas>
                    </div>
                    <div class="admin-chart-item admin-chart-item--small">
                        <canvas id="chartObjectifs" height="160"></canvas>
                    </div>
                </div>
                <div class="admin-chart-bottom">
                    <canvas id="chartTopRegimes" height="120"></canvas>
                </div>
            </div>
        </div>

        <aside class="dashboard-aside">
            <div class="card">
                <h4>Gestion Back-Office</h4>
                <div class="quick-actions">
                    <a class="quick-action" href="/gestion/regimes">
                        <span class="qa-icon">R</span>
                        <span>
                            <strong>Régimes</strong>
                            <small>Gérer les régimes</small>
                        </span>
                    </a>
                    <a class="quick-action" href="/gestion/activites-sportives">
                        <span class="qa-icon">A</span>
                        <span>
                            <strong>Activités</strong>
                            <small>Gérer les activités</small>
                        </span>
                    </a>
                    <a class="quick-action" href="/gestion/codes-wallet">
                        <span class="qa-icon">C</span>
                        <span>
                            <strong>Codes wallet</strong>
                            <small>Gérer les codes</small>
                        </span>
                    </a>
                    <a class="quick-action" href="/gestion/parametres">
                        <span class="qa-icon">P</span>
                        <span>
                            <strong>Paramètres</strong>
                            <small>Configurer la plateforme</small>
                        </span>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?= view('layouts/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function(){
        const charts = <?= json_encode($charts ?? []) ?>;

        // Registrations line
        const ctxR = document.getElementById('chartRegistrations');
        if (ctxR && charts.registrations) {
            new Chart(ctxR.getContext('2d'), {
                type: 'line',
                data: {
                    labels: charts.registrations.labels || [],
                    datasets: [{ label: 'Inscriptions', data: charts.registrations.data || [], borderColor: '#2da05c', backgroundColor: 'rgba(45,160,92,0.08)', fill:true }]
                }, options: { responsive:true, plugins:{legend:{display:false}} }
            });
        }

        // Objectifs pie
        const ctxO = document.getElementById('chartObjectifs');
        if (ctxO && charts.objectifs) {
            new Chart(ctxO.getContext('2d'), {
                type: 'pie',
                data: { labels: charts.objectifs.labels || [], datasets: [{ data: charts.objectifs.data || [], backgroundColor: ['#2da05c','#3dba6e','#1e7d44','#b5a88e','#f3d68b'] }] }, options: { responsive:true }
            });
        }

        // Top regimes bar
        const ctxT = document.getElementById('chartTopRegimes');
        if (ctxT && charts.topRegimes) {
            new Chart(ctxT.getContext('2d'), {
                type: 'bar',
                data: { labels: charts.topRegimes.labels || [], datasets: [{ label: 'Achats', data: charts.topRegimes.data || [], backgroundColor: '#2da05c' }] }, options: { responsive:true, plugins:{legend:{display:false}} }
            });
        }
    })();
</script>
