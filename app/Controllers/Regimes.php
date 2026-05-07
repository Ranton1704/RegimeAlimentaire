<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\RegimePrixModel;

class Regimes extends BaseController
{
    public function index()
    {
        $this->ensureAdmin();

        $regimeModel = new RegimeModel();
        $regimes = $regimeModel
            ->select('regimes.*, objectifs.nom AS objectif_nom')
            ->join('objectifs', 'objectifs.id = regimes.objectifs_id', 'left')
            ->orderBy('regimes.id', 'DESC')
            ->findAll();

        $pricesByRegime = [];
        $priceRows = (new RegimePrixModel())
            ->orderBy('duree_jours', 'ASC')
            ->findAll();

        foreach ($priceRows as $row) {
            $pricesByRegime[(int) $row['regime_id']][] = $row;
        }

        return view('regimes/index', [
            'regimes' => $regimes,
            'pricesByRegime' => $pricesByRegime,
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('regimes/form', [
            'action' => 'create',
            'objectifs' => (new ObjectifModel())->findAll(),
        ]);
    }

    public function store()
    {
        $this->ensureAdmin();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'objectifs_id' => (int) $this->request->getPost('objectifs_id'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'pourcentage_viande' => (float) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (float) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (float) $this->request->getPost('pourcentage_volaille'),
        ];

        $regimeModel = new RegimeModel();
        $regimeModel->insert($data);
        $regimeId = $regimeModel->getInsertID();

        $prix = (float) $this->request->getPost('prix');
        $duree = (int) $this->request->getPost('duree_jours');
        if ($regimeId && $prix > 0) {
            (new RegimePrixModel())->insert([
                'regime_id' => $regimeId,
                'prix' => $prix,
                'duree_jours' => $duree,
            ]);
        }

        return redirect()->to('/gestion/regimes')->with('success', 'Régime créé');
    }

    public function edit($id)
    {
        $this->ensureAdmin();
        $regime = (new RegimeModel())->find((int) $id);
        if (!$regime) {
            return redirect()->to('/gestion/regimes')->with('error', 'Régime introuvable');
        }

        $prices = (new RegimePrixModel())
            ->where('regime_id', (int) $id)
            ->orderBy('duree_jours', 'ASC')
            ->findAll();

        return view('regimes/form', [
            'action' => 'edit',
            'regime' => $regime,
            'prices' => $prices,
            'objectifs' => (new ObjectifModel())->findAll(),
        ]);
    }

    public function update($id)
    {
        $this->ensureAdmin();
        $regimeModel = new RegimeModel();
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'objectifs_id' => (int) $this->request->getPost('objectifs_id'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'pourcentage_viande' => (float) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (float) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (float) $this->request->getPost('pourcentage_volaille'),
        ];

        $regimeModel->update((int) $id, $data);

        return redirect()->to('/gestion/regimes')->with('success', 'Régime mis à jour');
    }

    public function delete($id)
    {
        $this->ensureAdmin();
        $regimeModel = new RegimeModel();
        $regimeModel->delete((int) $id);
        return redirect()->to('/gestion/regimes')->with('success', 'Régime supprimé');
    }

    public function show($id)
    {
        $this->ensureAdmin();

        $regime = (new RegimeModel())
            ->select('regimes.*, objectifs.nom AS objectif_nom')
            ->join('objectifs', 'objectifs.id = regimes.objectifs_id', 'left')
            ->where('regimes.id', (int) $id)
            ->first();

        if (!$regime) {
            return redirect()->to('/gestion/regimes')->with('error', 'Régime introuvable');
        }

        $prices = (new RegimePrixModel())
            ->where('regime_id', (int) $id)
            ->orderBy('duree_jours', 'ASC')
            ->findAll();

        return view('regimes/show', [
            'regime' => $regime,
            'prices' => $prices,
        ]);
    }

    private function ensureAdmin()
    {
        // Ici on vérifie le rôle 'ADMIN' en session; si pas admin, on bloque.
        $role = session()->get('role') ?? 'USER';
        if (strtoupper($role) !== 'ADMIN') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Accès non autorisé');
        }
    }
}
