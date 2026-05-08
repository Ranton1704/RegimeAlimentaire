<?= view('layouts/header', ['title' => 'Modifier mon profil']) ?>

<div class="container">
    <div class="dashboard-grid">
        <div class="dashboard-main">

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error visible" style="margin-bottom: 20px;">
                    <strong>⚠️</strong> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($user) && !empty($profil)): ?>
                <!-- HEADER -->
                <div class="card" style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <div>
                            <h2>Modifier mon profil</h2>
                            <p class="card-subtitle" style="margin: 4px 0 0;">Mettez à jour vos informations personnelles et données de santé.</p>
                        </div>
                    </div>
                </div>

                <form method="post" action="/profil/update">
                    <?= csrf_field() ?>

                    <!-- SECTION 1: INFORMATIONS PERSONNELLES -->
                    <div class="card">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            <h3>Informations personnelles</h3>
                        </div>

                        <div class="cards-row">
                            <div class="field">
                                <label for="prenom">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="<?= esc($user['prenom']) ?>" required>
                            </div>
                            <div class="field">
                                <label for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" value="<?= esc($user['nom']) ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">Adresse e-mail</label>
                            <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>" required>
                        </div>
                    </div>

                    <!-- SECTION 2: DONNÉES DE SANTÉ -->
                    <div class="card">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                            <h3>Données de santé</h3>
                        </div>

                        <div class="cards-row">
                            <div class="field">
                                <label for="taille">Taille (m)</label>
                                <input type="number" id="taille" step="0.01" name="taille" value="<?= esc((string) $profil['taille']) ?>" required>
                                <small style="color: #666;">Ex: 1.75</small>
                            </div>
                            <div class="field">
                                <label for="poids">Poids actuel (kg)</label>
                                <input type="number" id="poids" step="0.1" name="poids" value="<?= esc((string) $profil['poids']) ?>" required>
                                <small style="color: #666;">Ex: 70</small>
                            </div>
                        </div>

                        <div class="cards-row">
                            <div class="field">
                                <label for="poids_souhaite">Poids cible (kg)</label>
                                <input type="number" id="poids_souhaite" step="0.1" name="poids_souhaite" value="<?= esc((string) ($profil['poids_souhaite'] ?? $profil['poids'])) ?>" required>
                                <small style="color: #666;">Votre objectif</small>
                            </div>
                            <div class="field">
                                <label for="activite">Niveau d'activité</label>
                                <select id="activite" name="activite" required>
                                    <option value="">Choisir</option>
                                    <option value="Faible" <?= ($profil['activite'] ?? '') === 'Faible' ? 'selected' : '' ?>>Faible (sédentaire)</option>
                                    <option value="Moderee" <?= ($profil['activite'] ?? '') === 'Moderee' ? 'selected' : '' ?>>Modérée (1-3 jours/sem)</option>
                                    <option value="Intense" <?= ($profil['activite'] ?? '') === 'Intense' ? 'selected' : '' ?>>Intense (4+ jours/sem)</option>
                                </select>
                            </div>
                        </div>

                        <!-- IMC Preview -->
                        <div style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                            <p style="font-size: 14px; color: #666; margin-bottom: 8px;">📊 IMC actuel</p>
                            <p style="font-size: 24px; font-weight: 600; color: #2d7a6f;">
                                <span id="imc-preview"><?= esc((string) $profil['imc']) ?></span>
                            </p>
                            <p id="imc-category" style="font-size: 14px; color: #666; margin-top: 4px;">
                                <?php 
                                    $imc = (float) $profil['imc'];
                                    if ($imc < 18.5) {
                                        echo 'État: Insuffisant';
                                    } elseif ($imc < 25) {
                                        echo 'État: Normal ✅';
                                    } elseif ($imc < 30) {
                                        echo 'État: Surpoids ⚠️';
                                    } else {
                                        echo 'État: Obésité 🚨';
                                    }
                                ?>
                            </p>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div style="display: flex; gap: 12px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; text-align: center;">
                            <span style="margin-right: 8px;">✓</span> Enregistrer les modifications
                        </button>
                        <a href="/profil" class="btn btn-outline" style="flex: 1; text-align: center;">
                            <span style="margin-right: 8px;">✕</span> Annuler
                        </a>
                    </div>
                </form>

                <script>
                    // Live IMC calculation
                    const talleInput = document.getElementById('taille');
                    const poidsInput = document.getElementById('poids');
                    const imcPreview = document.getElementById('imc-preview');
                    const imcCategory = document.getElementById('imc-category');

                    function updateIMC() {
                        const taille = parseFloat(talleInput.value);
                        const poids = parseFloat(poidsInput.value);
                        if (taille > 0 && poids > 0) {
                            const imc = (poids / (taille * taille)).toFixed(2);
                            imcPreview.textContent = imc;
                            
                            if (imc < 18.5) {
                                imcCategory.textContent = 'État: Insuffisant';
                            } else if (imc < 25) {
                                imcCategory.textContent = 'État: Normal ✅';
                            } else if (imc < 30) {
                                imcCategory.textContent = 'État: Surpoids ⚠️';
                            } else {
                                imcCategory.textContent = 'État: Obésité 🚨';
                            }
                        }
                    }

                    talleInput.addEventListener('change', updateIMC);
                    poidsInput.addEventListener('change', updateIMC);
                </script>

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
