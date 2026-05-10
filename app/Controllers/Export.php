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
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $toPdf = static function ($value): string {
            $text = trim((string) $value);
            if ($text === '') {
                return '-';
            }
            $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
            return utf8_decode($text);
        };

        $drawSectionTitle = static function (\FPDF $pdfInstance, string $title) use ($toPdf): void {
            $pdfInstance->Ln(4);
            $pdfInstance->SetFillColor(240, 250, 244);
            $pdfInstance->SetTextColor(30, 125, 68);
            $pdfInstance->SetFont('Arial', 'B', 12);
            $pdfInstance->Cell(0, 9, '  ' . $toPdf($title), 0, 1, 'L', true);
            $pdfInstance->SetTextColor(35, 43, 40);
            $pdfInstance->Ln(1);
        };

        $drawMetaLine = static function (\FPDF $pdfInstance, string $label, string $value) use ($toPdf): void {
            $pdfInstance->SetFont('Arial', 'B', 10);
            $pdfInstance->SetTextColor(90, 99, 96);
            $pdfInstance->Cell(34, 7, $toPdf($label), 0, 0);
            $pdfInstance->SetFont('Arial', '', 10);
            $pdfInstance->SetTextColor(26, 31, 28);
            $pdfInstance->Cell(0, 7, $toPdf($value), 0, 1);
        };

        $drawCard = static function (\FPDF $pdfInstance, string $title, string $description, string $meta) use ($toPdf): void {
            $pdfInstance->SetFillColor(252, 252, 250);
            $pdfInstance->SetDrawColor(224, 216, 200);
            $pdfInstance->SetLineWidth(0.2);

            $startX = $pdfInstance->GetX();
            $startY = $pdfInstance->GetY();
            $cardWidth = 180;

            $pdfInstance->Rect($startX, $startY, $cardWidth, 24, 'DF');
            $pdfInstance->SetXY($startX + 3, $startY + 2);

            $pdfInstance->SetFont('Arial', 'B', 10);
            $pdfInstance->SetTextColor(26, 31, 28);
            $pdfInstance->Cell($cardWidth - 6, 6, $toPdf($title), 0, 1, 'L');

            $pdfInstance->SetFont('Arial', '', 9);
            $pdfInstance->SetTextColor(90, 99, 96);
            $pdfInstance->SetX($startX + 3);
            $pdfInstance->MultiCell($cardWidth - 6, 5, $toPdf($description), 0, 'L');

            $pdfInstance->SetFont('Arial', '', 9);
            $pdfInstance->SetTextColor(30, 125, 68);
            $pdfInstance->SetX($startX + 3);
            $pdfInstance->Cell($cardWidth - 6, 5, $toPdf($meta), 0, 1, 'L');

            $pdfInstance->SetY(max($pdfInstance->GetY() + 1, $startY + 26));
        };

        $pdf->SetFillColor(30, 125, 68);
        $pdf->SetDrawColor(30, 125, 68);
        $pdf->Rect(15, 15, 180, 24, 'F');

        $pdf->SetXY(20, 21);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 6, $toPdf('Regime Sante - Profil utilisateur'), 0, 1, 'L');

        $pdf->SetX(20);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, $toPdf('Export genere le ' . date('d/m/Y a H:i')), 0, 1, 'L');

        $pdf->SetTextColor(26, 31, 28);
        $pdf->SetY(44);

        $drawSectionTitle($pdf, 'Informations personnelles');
        $drawMetaLine($pdf, 'Utilisateur', trim((string) (($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''))));
        $drawMetaLine($pdf, 'Email', (string) ($user['email'] ?? '-'));
        $drawMetaLine($pdf, 'Genre', (string) ($user['genre'] ?? '-'));
        if (!empty($profil)) {
            $drawMetaLine($pdf, 'Taille', (string) $profil['taille'] . ' m');
            $drawMetaLine($pdf, 'Poids', (string) $profil['poids'] . ' kg');
            $drawMetaLine($pdf, 'Age', (string) $profil['age']);
            $drawMetaLine($pdf, 'IMC', (string) $profil['imc']);
        }

        $drawSectionTitle($pdf, 'Objectifs');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(26, 31, 28);
        if (empty($objectifs)) {
            $pdf->Cell(0, 7, $toPdf('Aucun objectif selectionne.'), 0, 1);
        } else {
            foreach ($objectifs as $objectif) {
                $pdf->Cell(0, 7, $toPdf('- ' . ($objectif['nom'] ?? 'Objectif')), 0, 1);
            }
        }

        $drawSectionTitle($pdf, 'Regimes recommandes');
        if (empty($regimes)) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, $toPdf('Aucun regime recommande pour le moment.'), 0, 1);
        } else {
            foreach ($regimes as $regime) {
                $price = $regimePrixModel->where('regime_id', (int) $regime['id'])->orderBy('prix', 'ASC')->first();
                $basePrice = (float) ($price['prix'] ?? 0);
                $finalPrice = round($basePrice * (1 - $discountRate), 2);
                $meta = 'Variation: ' . ($regime['variation_poids'] ?? 'N/A')
                    . ' kg  |  Prix: ' . number_format($finalPrice, 2, ',', ' ') . ' Ar'
                    . ($isGold ? ' (Gold -' . $goldReductionLabel . '%)' : '')
                    . '  |  Duree: ' . ($price['duree_jours'] ?? 'N/A') . ' jours';

                $drawCard(
                    $pdf,
                    (string) ($regime['nom'] ?? 'Regime'),
                    (string) ($regime['description'] ?? ''),
                    $meta
                );
            }
        }

        $drawSectionTitle($pdf, 'Activites sportives recommandees');
        if (empty($activities)) {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, $toPdf('Aucune activite disponible pour le moment.'), 0, 1);
        } else {
            foreach ($activities as $activity) {
                $meta = 'Duree: ' . ($activity['duree_minutes'] ?? 'N/A') . ' min';
                $drawCard(
                    $pdf,
                    (string) ($activity['nom'] ?? 'Activite'),
                    (string) ($activity['description'] ?? ''),
                    $meta
                );
            }
        }

        $drawSectionTitle($pdf, 'Resume');
        $drawMetaLine($pdf, 'Solde wallet', number_format((float) ($user['solde'] ?? 0), 2, ',', ' ') . ' Ar');
        $drawMetaLine($pdf, 'Statut Gold', !empty($user['is_gold']) ? 'Oui' : 'Non');
        if ($isGold) {
            $drawMetaLine($pdf, 'Remise Gold', (string) $goldReductionLabel . '% sur les regimes');
        }

        $pdf->Output('I', 'export-regime-sante.pdf');
        exit;
    }
}
