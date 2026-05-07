<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;
use App\Models\ObjectifModel;
use App\Models\ObjectifUserModel;
use App\Models\ProfilSanteModel;
use App\Models\ParametreModel;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;
use App\Models\UserModel;

class Export extends BaseController
{
    public function pdf()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        require_once ROOTPATH . 'fpdf186/fpdf.php';

        $userId = (int) session()->get('user_id');
        $user = (new UserModel())->find($userId);
        $profil = (new ProfilSanteModel())->where('users_id', $userId)->first();

        $objectifs = (new ObjectifModel())
            ->select('objectifs.nom')
            ->join('objectifs_users', 'objectifs_users.objectifs_id = objectifs.id', 'inner')
            ->where('objectifs_users.users_id', $userId)
            ->findAll();

        $selectedIds = (new ObjectifUserModel())
            ->select('objectifs_id')
            ->where('users_id', $userId)
            ->findAll();
        $selectedIds = array_map(static fn($r) => (int) $r['objectifs_id'], $selectedIds ?: []);

        $regimeModel = new RegimeModel();
        $regimes = !empty($selectedIds)
            ? $regimeModel->whereIn('objectifs_id', $selectedIds)->findAll(3)
            : $regimeModel->findAll(3);

        $activities = !empty($selectedIds)
            ? (new ActiviteSportiveModel())->whereIn('objectifs_id', $selectedIds)->findAll(3)
            : (new ActiviteSportiveModel())->findAll(3);

        $regimePrixModel = new RegimePrixModel();
        $isGold = !empty($user['is_gold']);
        $discountRate = $isGold ? ((float) (new ParametreModel())->getValue('reduction_gold', 15) / 100) : 0.0;
        $goldReductionLabel = (int) (new ParametreModel())->getValue('reduction_gold', 15);

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->SetMargins(15, 15, 15);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Regime Santé - Export Profil'), 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode('Utilisateur : ' . ($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')), 0, 1);
        $pdf->Cell(0, 8, utf8_decode('Email : ' . ($user['email'] ?? '')), 0, 1);
        $pdf->Cell(0, 8, utf8_decode('Genre : ' . ($user['genre'] ?? '')), 0, 1);

        if (!empty($profil)) {
            $pdf->Cell(0, 8, utf8_decode('Taille : ' . $profil['taille'] . ' m'), 0, 1);
            $pdf->Cell(0, 8, utf8_decode('Poids : ' . $profil['poids'] . ' kg'), 0, 1);
            $pdf->Cell(0, 8, utf8_decode('Age : ' . $profil['age']), 0, 1);
            $pdf->Cell(0, 8, utf8_decode('IMC : ' . $profil['imc']), 0, 1);
        }

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, utf8_decode('Objectifs'), 0, 1);
        $pdf->SetFont('Arial', '', 11);
        foreach ($objectifs as $objectif) {
            $pdf->Cell(0, 7, utf8_decode('- ' . $objectif['nom']), 0, 1);
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, utf8_decode('Régimes recommandés'), 0, 1);
        $pdf->SetFont('Arial', '', 10);
        foreach ($regimes as $regime) {
            $price = $regimePrixModel->where('regime_id', (int) $regime['id'])->orderBy('prix', 'ASC')->first();
            $basePrice = (float) ($price['prix'] ?? 0);
            $finalPrice = round($basePrice * (1 - $discountRate), 2);
            $line = sprintf(
                '%s | %s | Variation: %s kg | Prix: %s Ar%s | Durée: %s jours',
                $regime['nom'],
                $regime['description'],
                $regime['variation_poids'],
                number_format($finalPrice, 2, ',', ' '),
                $isGold ? ' (remise Gold -' . $goldReductionLabel . '%)' : '',
                $price['duree_jours'] ?? 'N/A'
            );
            $pdf->MultiCell(0, 6, utf8_decode($line));
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, utf8_decode('Activités sportives recommandées'), 0, 1);
        $pdf->SetFont('Arial', '', 10);
        foreach ($activities as $activity) {
            $line = sprintf(
                '%s | %s | Durée: %s min',
                $activity['nom'],
                $activity['description'],
                $activity['duree_minutes'] ?? 'N/A'
            );
            $pdf->MultiCell(0, 6, utf8_decode($line));
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, utf8_decode('Résumé'), 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode('Solde wallet : ' . ($user['solde'] ?? 0) . ' Ar'), 0, 1);
        $pdf->Cell(0, 7, utf8_decode('Statut Gold : ' . (!empty($user['is_gold']) ? 'Oui' : 'Non')), 0, 1);
        if ($isGold) {
            $pdf->Cell(0, 7, utf8_decode('Remise appliquée sur les régimes : ' . $goldReductionLabel . '%'), 0, 1);
        }

        $pdf->Output('I', 'export-regime-sante.pdf');
        exit;
    }
}
