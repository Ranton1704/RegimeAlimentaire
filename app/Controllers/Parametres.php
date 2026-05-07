<?php

namespace App\Controllers;

use App\Models\ParametreModel;

class Parametres extends BaseController
{
    public function index()
    {
        $this->ensureAdmin();

        return view('parametres/index', [
            'parametres' => (new ParametreModel())->orderBy('cle', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('parametres/form', ['action' => 'create']);
    }

    public function store()
    {
        $this->ensureAdmin();

        $model = new ParametreModel();
        $cle = trim((string) $this->request->getPost('cle'));
        $valeur = trim((string) $this->request->getPost('valeur'));

        if ($cle === '' || $valeur === '') {
            return redirect()->to('/gestion/parametres/create')->with('error', 'Clé et valeur obligatoires');
        }

        if ($model->where('cle', $cle)->first()) {
            return redirect()->to('/gestion/parametres/create')->with('error', 'Cette clé existe déjà');
        }

        $model->insert([
            'cle' => $cle,
            'valeur' => $valeur,
        ]);

        return redirect()->to('/gestion/parametres')->with('success', 'Paramètre créé');
    }

    public function edit($id)
    {
        $this->ensureAdmin();

        $parametre = (new ParametreModel())->find((int) $id);
        if (!$parametre) {
            return redirect()->to('/gestion/parametres')->with('error', 'Paramètre introuvable');
        }

        return view('parametres/form', [
            'action' => 'edit',
            'parametre' => $parametre,
        ]);
    }

    public function update($id)
    {
        $this->ensureAdmin();

        $model = new ParametreModel();
        $existing = $model->find((int) $id);
        if (!$existing) {
            return redirect()->to('/gestion/parametres')->with('error', 'Paramètre introuvable');
        }

        $cle = trim((string) $this->request->getPost('cle'));
        $valeur = trim((string) $this->request->getPost('valeur'));

        if ($cle === '' || $valeur === '') {
            return redirect()->to('/gestion/parametres/edit/' . (int) $id)->with('error', 'Clé et valeur obligatoires');
        }

        $duplicate = $model->where('cle', $cle)->where('id !=', (int) $id)->first();
        if ($duplicate) {
            return redirect()->to('/gestion/parametres/edit/' . (int) $id)->with('error', 'Une autre ligne utilise déjà cette clé');
        }

        $model->update((int) $id, [
            'cle' => $cle,
            'valeur' => $valeur,
        ]);

        return redirect()->to('/gestion/parametres')->with('success', 'Paramètre mis à jour');
    }

    public function delete($id)
    {
        $this->ensureAdmin();

        (new ParametreModel())->delete((int) $id);

        return redirect()->to('/gestion/parametres')->with('success', 'Paramètre supprimé');
    }

    private function ensureAdmin()
    {
        $role = session()->get('role') ?? 'USER';
        if (strtoupper($role) !== 'ADMIN') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Accès non autorisé');
        }
    }
}
