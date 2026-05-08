<?php

namespace App\Controllers;

use App\Models\PortefeuilleCodeModel;
use App\Models\PortefeuilleTransactionModel;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;
use App\Models\RegimeUserModel;
use App\Models\UserModel;
use App\Models\ParametreModel;

class Wallet extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        $user = (new UserModel())->find($userId);
        $transactions = (new PortefeuilleTransactionModel())
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(10);

        return view('wallet/index', [
            'user' => $user,
            'transactions' => $transactions,
        ]);
    }

    public function recharge()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $code = trim((string) $this->request->getPost('code'));
        if ($code === '') {
            return redirect()->to('/wallet')->with('error', 'Veuillez saisir un code');
        }

        $userId = (int) session()->get('user_id');
        $codeModel = new PortefeuilleCodeModel();
        $codeRow = $codeModel->where('code', $code)->first();

        if (!$codeRow) {
            return redirect()->to('/wallet')->with('error', 'Code invalide');
        }

        if (!empty($codeRow['utilise'])) {
            return redirect()->to('/wallet')->with('error', 'Code déjà utilisé');
        }

        $userModel = new UserModel();
        $transactionModel = new PortefeuilleTransactionModel();

        $user = $userModel->find($userId);
        $newBalance = (float) ($user['solde'] ?? 0) + (float) $codeRow['montant'];

        $userModel->update($userId, ['solde' => $newBalance]);
        $codeModel->update($codeRow['id'], [
            'utilise' => true,
            'utilise_le' => date('Y-m-d'),
            'used_by' => $userId,
        ]);

        $transactionModel->insert([
            'user_id' => $userId,
            'type' => 'RECHARGE',
            'montant' => (float) $codeRow['montant'],
            'description' => 'Recharge via code wallet',
        ]);

        return redirect()->to('/wallet')->with('success', 'Wallet rechargé avec succès');
    }

    public function buyGold()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = (int) session()->get('user_id');
        $userModel = new UserModel();
        $transactionModel = new PortefeuilleTransactionModel();

        $user = $userModel->find($userId);
        if (!empty($user['is_gold'])) {
            return redirect()->to('/wallet')->with('error', 'Vous êtes déjà Gold');
        }

        $goldPrice = (float) (new ParametreModel())->getValue('gold_prix', 50000);
        if ($goldPrice <= 0) {
            $goldPrice = 50000.00;
        }
        $balance = (float) ($user['solde'] ?? 0);

        if ($balance < $goldPrice) {
            return redirect()->to('/wallet')->with('error', 'Solde insuffisant pour acheter Gold');
        }

        $newBalance = $balance - $goldPrice;
        $userModel->update($userId, [
            'solde' => $newBalance,
            'is_gold' => true,
        ]);

        $transactionModel->insert([
            'user_id' => $userId,
            'type' => 'ACHAT_GOLD',
            'montant' => $goldPrice,
            'description' => 'Achat de l\'option Gold',
        ]);

        return redirect()->to('/wallet')->with('success', 'Option Gold activée');
    }

    public function buyRegime($regimeId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $regimeId = (int) $regimeId;
        $userId = (int) session()->get('user_id');

        $regime = (new RegimeModel())->find($regimeId);
        if (!$regime) {
            return redirect()->to('/dashboard')->with('error', 'Régime introuvable');
        }

        $priceRow = (new RegimePrixModel())
            ->where('regime_id', $regimeId)
            ->orderBy('prix', 'ASC')
            ->first();

        if (!$priceRow) {
            return redirect()->to('/dashboard')->with('error', 'Aucun tarif disponible pour ce régime');
        }

        $price = (float) ($priceRow['prix'] ?? 0);
        $durationDays = max(1, (int) ($priceRow['duree_jours'] ?? 30));

        $userModel = new UserModel();
        $transactionModel = new PortefeuilleTransactionModel();
        $regimeUserModel = new RegimeUserModel();

        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to('/login');
        }

        $balance = (float) ($user['solde'] ?? 0);
        if ($balance < $price) {
            return redirect()->to('/wallet')->with('error', 'Solde insuffisant pour acheter ce régime');
        }

        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime('+' . ($durationDays - 1) . ' days'));

        $db = \Config\Database::connect();
        $db->transStart();

        $userModel->update($userId, ['solde' => $balance - $price]);

        $regimeUserModel->insert([
            'users_id' => $userId,
            'regime_id' => $regimeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]);

        $transactionModel->insert([
            'user_id' => $userId,
            'regime_id' => $regimeId,
            'type' => 'ACHAT_REGIME',
            'montant' => $price,
            'description' => 'Achat du régime: ' . ($regime['nom'] ?? ('#' . $regimeId)),
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->to('/dashboard')->with('error', 'Achat impossible, veuillez réessayer');
        }

        return redirect()->to('/dashboard')->with('success', 'Régime acheté avec succès');
    }
}
