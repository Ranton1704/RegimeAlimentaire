<?php

namespace App\Controllers;

use App\Models\PortefeuilleCodeModel;
use App\Models\UserModel;

class CodesWallet extends BaseController
{
    public function index()
    {
        $this->ensureAdmin();

        $codes = (new PortefeuilleCodeModel())
            ->select('portfeuille_code.*, CONCAT(users.nom, " ", users.prenom) AS used_by_name')
            ->join('users', 'users.id = portfeuille_code.used_by', 'left')
            ->orderBy('portfeuille_code.id', 'DESC')
            ->findAll();

        return view('codes_wallet/index', [
            'codes' => $codes,
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('codes_wallet/form', [
            'action' => 'create',
            'users' => (new UserModel())->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $this->ensureAdmin();

        $code = trim((string) $this->request->getPost('code'));
        $montant = (float) $this->request->getPost('montant');
        $description = trim((string) $this->request->getPost('description'));

        if ($code === '' || $montant <= 0) {
            return redirect()->to('/gestion/codes-wallet/create')->with('error', 'Code et montant obligatoires');
        }

        $codeModel = new PortefeuilleCodeModel();
        if ($codeModel->where('code', $code)->first()) {
            return redirect()->to('/gestion/codes-wallet/create')->with('error', 'Code déjà existant');
        }

        $codeModel->insert([
            'code' => $code,
            'description' => $description,
            'montant' => $montant,
            'utilise' => false,
            'utilise_le' => null,
            'used_by' => null,
        ]);

        return redirect()->to('/gestion/codes-wallet')->with('success', 'Code créé');
    }

    public function edit($id)
    {
        $this->ensureAdmin();

        $code = (new PortefeuilleCodeModel())->find((int) $id);
        if (!$code) {
            return redirect()->to('/gestion/codes-wallet')->with('error', 'Code introuvable');
        }

        return view('codes_wallet/form', [
            'action' => 'edit',
            'code' => $code,
            'users' => (new UserModel())->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $this->ensureAdmin();

        $code = trim((string) $this->request->getPost('code'));
        $montant = (float) $this->request->getPost('montant');
        $description = trim((string) $this->request->getPost('description'));
        $utilise = (bool) $this->request->getPost('utilise');
        $usedBy = $this->request->getPost('used_by');

        $codeModel = new PortefeuilleCodeModel();
        $existing = $codeModel->find((int) $id);
        if (!$existing) {
            return redirect()->to('/gestion/codes-wallet')->with('error', 'Code introuvable');
        }

        $data = [
            'code' => $code !== '' ? $code : $existing['code'],
            'description' => $description,
            'montant' => $montant > 0 ? $montant : (float) $existing['montant'],
            'utilise' => $utilise,
            'used_by' => $utilise && $usedBy !== '' ? (int) $usedBy : null,
            'utilise_le' => $utilise ? ($existing['utilise_le'] ?? date('Y-m-d')) : null,
        ];

        $duplicate = $codeModel->where('code', $data['code'])->where('id !=', (int) $id)->first();
        if ($duplicate) {
            return redirect()->to('/gestion/codes-wallet/edit/' . (int) $id)->with('error', 'Un autre code porte déjà cette valeur');
        }

        $codeModel->update((int) $id, $data);

        return redirect()->to('/gestion/codes-wallet')->with('success', 'Code mis à jour');
    }

    public function delete($id)
    {
        $this->ensureAdmin();

        (new PortefeuilleCodeModel())->delete((int) $id);

        return redirect()->to('/gestion/codes-wallet')->with('success', 'Code supprimé');
    }

    private function ensureAdmin()
    {
        $role = session()->get('role') ?? 'USER';
        if (strtoupper($role) !== 'ADMIN') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Accès non autorisé');
        }
    }
}
