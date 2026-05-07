<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;
use App\Models\ObjectifModel;

class ActivitesSportives extends BaseController
{
    public function index()
    {
        $this->ensureAdmin();

        $activites = (new ActiviteSportiveModel())
            ->select('activite_sportive.*, objectifs.nom AS objectif_nom')
            ->join('objectifs', 'objectifs.id = activite_sportive.objectifs_id', 'left')
            ->orderBy('activite_sportive.id', 'DESC')
            ->findAll();

        return view('activites_sportives/index', [
            'activites' => $activites,
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('activites_sportives/form', [
            'action' => 'create',
            'objectifs' => (new ObjectifModel())->findAll(),
        ]);
    }

    public function store()
    {
        $this->ensureAdmin();

        (new ActiviteSportiveModel())->insert([
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'objectifs_id' => (int) $this->request->getPost('objectifs_id'),
            'duree_minutes' => (int) $this->request->getPost('duree_minutes'),
        ]);

        return redirect()->to('/gestion/activites-sportives')->with('success', 'Activité créée');
    }

    public function edit($id)
    {
        $this->ensureAdmin();

        $activite = (new ActiviteSportiveModel())->find((int) $id);
        if (!$activite) {
            return redirect()->to('/gestion/activites-sportives')->with('error', 'Activité introuvable');
        }

        return view('activites_sportives/form', [
            'action' => 'edit',
            'activite' => $activite,
            'objectifs' => (new ObjectifModel())->findAll(),
        ]);
    }

    public function update($id)
    {
        $this->ensureAdmin();

        (new ActiviteSportiveModel())->update((int) $id, [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'objectifs_id' => (int) $this->request->getPost('objectifs_id'),
            'duree_minutes' => (int) $this->request->getPost('duree_minutes'),
        ]);

        return redirect()->to('/gestion/activites-sportives')->with('success', 'Activité mise à jour');
    }

    public function delete($id)
    {
        $this->ensureAdmin();

        (new ActiviteSportiveModel())->delete((int) $id);

        return redirect()->to('/gestion/activites-sportives')->with('success', 'Activité supprimée');
    }

    public function show($id)
    {
        $this->ensureAdmin();

        $activite = (new ActiviteSportiveModel())
            ->select('activite_sportive.*, objectifs.nom AS objectif_nom')
            ->join('objectifs', 'objectifs.id = activite_sportive.objectifs_id', 'left')
            ->where('activite_sportive.id', (int) $id)
            ->first();

        if (!$activite) {
            return redirect()->to('/gestion/activites-sportives')->with('error', 'Activité introuvable');
        }

        return view('activites_sportives/show', [
            'activite' => $activite,
        ]);
    }

    private function ensureAdmin()
    {
        $role = session()->get('role') ?? 'USER';
        if (strtoupper($role) !== 'ADMIN') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Accès non autorisé');
        }
    }
}
