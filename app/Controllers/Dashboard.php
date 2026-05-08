<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\ObjectifUserModel;
use App\Models\ProfilSanteModel;
use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;
use App\Models\ActiviteSportiveModel;
use App\Models\PortefeuilleTransactionModel;
use App\Models\RegimeUserModel;
use App\Models\ParametreModel;
use App\Models\PortefeuilleCodeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');

        $user = (new UserModel())->find($userId);
        if (!$user) {
            session()->destroy();
            return redirect()->to('/login');
        }

        // Si administrateur, afficher dashboard admin
        $role = strtoupper((string) ($user['role'] ?? session()->get('role') ?? 'USER'));
        if ($role === 'ADMIN') {
            return $this->adminDashboard($user);
        }

        $profil = (new ProfilSanteModel())
            ->where('users_id', $userId)
            ->first();

        $objectifs = (new ObjectifModel())
            ->select('objectifs.id, objectifs.nom')
            ->join('objectifs_users', 'objectifs_users.objectifs_id = objectifs.id', 'inner')
            ->where('objectifs_users.users_id', $userId)
            ->findAll();

        // recommendations: prefer regimes matching user's selected objectifs
        $selectedIds = (new ObjectifUserModel())
            ->select('objectifs_id')
            ->where('users_id', $userId)
            ->findAll();

        $selectedIds = array_map(static fn($r) => (int) $r['objectifs_id'], $selectedIds ?: []);

        $regimeModel = new RegimeModel();
        if (!empty($selectedIds)) {
            $regimes = $regimeModel->whereIn('objectifs_id', $selectedIds)->findAll(3);
        } else {
            $regimes = $regimeModel->findAll(3);
        }

        $regimePrixModel = new RegimePrixModel();
        $activiteModel = new ActiviteSportiveModel();
        $isGold = !empty($user['is_gold']);
        $paramModel = new ParametreModel();
        $goldReduction = (float) $paramModel->getValue('gold_reduction_taux', 15);
        $discountRate = $isGold ? (max(0, $goldReduction) / 100) : 0.0;

        $recommendations = [];
        foreach ($regimes as $regime) {
            $priceRow = $regimePrixModel->where('regime_id', (int) $regime['id'])->orderBy('prix', 'ASC')->first();
            $basePrice = (float) ($priceRow['prix'] ?? 0);
            $finalPrice = round($basePrice * (1 - $discountRate), 2);
            $recommendations[] = [
                'regime' => $regime,
                'prix_base' => $basePrice,
                'prix' => $finalPrice,
                'discount_rate' => $discountRate,
                'duree_jours' => $priceRow['duree_jours'] ?? null,
            ];
        }

        $activities = [];
        if (!empty($selectedIds)) {
            $activities = $activiteModel->whereIn('objectifs_id', $selectedIds)->orderBy('duree_minutes', 'ASC')->findAll(3);
        }

        if (empty($activities)) {
            $activities = $activiteModel->findAll(3);
        }

        $targetWeight = (float) (session()->get('onboarding_health')['poids_souhaite'] ?? ($profil['poids'] ?? 0));
        $currentWeight = (float) ($profil['poids'] ?? 0);
        $estimatedChange = $targetWeight - $currentWeight;

        $weightForecast = [];
        foreach ($recommendations as $item) {
            $days = max(1, (int) ($item['duree_jours'] ?? 30));
            $dailyVariation = ((float) ($item['regime']['variation_poids'] ?? 0)) / max(1, $days);
            $estimatedTotal = round($dailyVariation * $days, 2);
            $forecastWeight = round($currentWeight + $estimatedTotal, 2);

            $weightForecast[] = [
                'regime' => $item['regime'],
                'jours' => $days,
                'delta' => $estimatedTotal,
                'poids_final' => $forecastWeight,
            ];
        }

        $transactionModel = new PortefeuilleTransactionModel();
        $allTx = $transactionModel
            ->select('type, montant')
            ->where('user_id', $userId)
            ->findAll();

        $walletStats = [
            'recharge' => 0.0,
            'achat_regime' => 0.0,
            'achat_gold' => 0.0,
        ];

        foreach ($allTx as $tx) {
            $amount = (float) ($tx['montant'] ?? 0);
            $type = (string) ($tx['type'] ?? '');

            if ($type === 'RECHARGE') {
                $walletStats['recharge'] += $amount;
            } elseif ($type === 'ACHAT_REGIME') {
                $walletStats['achat_regime'] += $amount;
            } elseif ($type === 'ACHAT_GOLD') {
                $walletStats['achat_gold'] += $amount;
            }
        }

        $totalSpent = $walletStats['achat_regime'] + $walletStats['achat_gold'];
        $spentBase = max(1.0, $totalSpent);

        $walletBars = [
            'regimes' => round(($walletStats['achat_regime'] / $spentBase) * 100, 2),
            'gold' => round(($walletStats['achat_gold'] / $spentBase) * 100, 2),
        ];

        $activeRegimes = (new RegimeUserModel())
            ->where('users_id', $userId)
            ->where('date_fin >=', date('Y-m-d'))
            ->countAllResults();

        $statsTable = [];
        foreach ($recommendations as $item) {
            $statsTable[] = [
                'regime' => $item['regime']['nom'] ?? '-',
                'duree' => (int) ($item['duree_jours'] ?? 0),
                'prix' => (float) ($item['prix'] ?? 0),
                'variation' => (float) ($item['regime']['variation_poids'] ?? 0),
            ];
        }

        // IMC category
        $taille = (float) ($profil['taille'] ?? 0);
        $poids = (float) ($profil['poids'] ?? 0);
        $imc = ($taille > 0 && $poids > 0) ? round($poids / ($taille * $taille), 2) : ($profil['imc'] ?? null);
        $imcCategory = 'Inconnu';
        if ($imc !== null) {
            $imc = (float) $imc;
            if ($imc < 18.5) {
                $imcCategory = 'Insuffisance pondérale';
            } elseif ($imc < 25) {
                $imcCategory = 'Poids normal';
            } elseif ($imc < 30) {
                $imcCategory = 'Surpoids';
            } else {
                $imcCategory = 'Obésité';
            }
        }

        return view('dashboard/index', [
            'profil' => $profil,
            'objectifs' => $objectifs,
            'user' => $user,
            'regimes' => $regimes,
            'recommendations' => $recommendations,
            'activities' => $activities,
            'weightForecast' => $weightForecast,
            'estimatedChange' => $estimatedChange,
            'imcCategory' => $imcCategory,
            'isGold' => $isGold,
            'walletStats' => $walletStats,
            'walletBars' => $walletBars,
            'activeRegimes' => $activeRegimes,
            'statsTable' => $statsTable,
        ]);
    }

    private function adminDashboard(array $user)
    {
        $userModel = new UserModel();
        $regimeUserModel = new RegimeUserModel();
        $transactionModel = new PortefeuilleTransactionModel();
        $codeModel = new PortefeuilleCodeModel();

        $totalUsers = (int) $userModel->countAllResults();
        $goldUsers = (int) $userModel->where('is_gold', 1)->countAllResults();
        $regimesAchetes = (int) $regimeUserModel->countAllResults();
        $codesUtilises = (int) $codeModel->where('utilise', 1)->countAllResults();

        $revenuRegimes = (float) ($transactionModel->selectSum('montant', 'total')->where('type', 'ACHAT_REGIME')->first()['total'] ?? 0);
        $revenuGold = (float) ($transactionModel->selectSum('montant', 'total')->where('type', 'ACHAT_GOLD')->first()['total'] ?? 0);
        $revenuTotal = $revenuRegimes + $revenuGold;

        // Préparer séries pour graphiques
        $db = \Config\Database::connect();

        // Inscriptions des 6 derniers mois (sans dépendre d'une colonne date sur users)
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = date('Y-m', strtotime("-{$i} months"));
        }
        $registrationsLabels = $months;
        $registrationsData = array_fill(0, count($months), 0);
        $registrationsData[count($registrationsData) - 1] = $totalUsers;

        // Répartition des objectifs
        $objectifsRows = $db->query("SELECT o.nom, COUNT(*) AS cnt FROM objectifs_users ou JOIN objectifs o ON o.id = ou.objectifs_id GROUP BY o.nom")->getResultArray();
        $objectifsLabels = array_map(static fn($r) => $r['nom'], $objectifsRows ?: []);
        $objectifsData = array_map(static fn($r) => (int) $r['cnt'], $objectifsRows ?: []);

        // Top régimes achetés
        $topRegimesRows = $db->query("SELECT r.nom, COUNT(*) AS cnt FROM regimes_users ru JOIN regimes r ON r.id = ru.regime_id GROUP BY r.nom ORDER BY cnt DESC LIMIT 6")->getResultArray();
        $topRegimesLabels = array_map(static fn($r) => $r['nom'], $topRegimesRows ?: []);
        $topRegimesData = array_map(static fn($r) => (int) $r['cnt'], $topRegimesRows ?: []);

        return view('dashboard/admin', [
            'user' => $user,
            'stats' => [
                'total_users' => $totalUsers,
                'gold_users' => $goldUsers,
                'regimes_achetes' => $regimesAchetes,
                'codes_utilises' => $codesUtilises,
                'revenu_regimes' => $revenuRegimes,
                'revenu_gold' => $revenuGold,
                'revenu_total' => $revenuTotal,
            ],
            'charts' => [
                'registrations' => [
                    'labels' => $registrationsLabels,
                    'data' => $registrationsData,
                ],
                'objectifs' => [
                    'labels' => $objectifsLabels,
                    'data' => $objectifsData,
                ],
                'topRegimes' => [
                    'labels' => $topRegimesLabels,
                    'data' => $topRegimesData,
                ],
            ],
        ]);
    }

    public function objectifs()
    {
        $objectifs = (new ObjectifModel())->findAll();
        $selected = (new ObjectifUserModel())
            ->select('objectifs_id')
            ->where('users_id', (int) session()->get('user_id'))
            ->findAll();

        $selectedIds = array_map(static fn(array $row): int => (int) $row['objectifs_id'], $selected);

        return view('dashboard/objectifs', [
            'objectifs' => $objectifs,
            'selectedIds' => $selectedIds,
        ]);
    }

    public function saveObjectifs()
    {
        $selected = $this->request->getPost('objectifs');
        if (!is_array($selected)) {
            return redirect()->to('/objectifs')->with('error', 'Sélectionnez au moins un objectif');
        }

        $selectedIds = array_values(array_unique(array_map('intval', $selected)));
        if (count($selectedIds) < 1 || count($selectedIds) > 3) {
            return redirect()->to('/objectifs')->with('error', 'Vous pouvez choisir entre 1 et 3 objectifs');
        }

        $objectifModel = new ObjectifModel();
        $availableIds = array_map(static fn(array $objectif): int => (int) $objectif['id'], $objectifModel->findAll());

        foreach ($selectedIds as $objectifId) {
            if (!in_array($objectifId, $availableIds, true)) {
                return redirect()->to('/objectifs')->with('error', 'Objectif invalide sélectionné');
            }
        }

        $pivot = new ObjectifUserModel();
        $userId = (int) session()->get('user_id');

        $pivot->where('users_id', $userId)->delete();

        foreach ($selectedIds as $objectifId) {
            $pivot->insert([
                'users_id' => $userId,
                'objectifs_id' => $objectifId,
            ]);
        }

        return redirect()->to('/dashboard')->with('success', 'Objectifs enregistrés');
    }
}