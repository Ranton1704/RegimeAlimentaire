<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilSanteModel;

class Profil extends BaseController
{
    protected $userModel;
    protected $profilSanteModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profilSanteModel = new ProfilSanteModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($userId);
        $profil = $this->profilSanteModel->where('users_id', $userId)->first();

        return view('profil/index', [
            'user' => $user,
            'profil' => $profil,
        ]);
    }

    public function edit()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($userId);
        $profil = $this->profilSanteModel->where('users_id', $userId)->first();

        return view('profil/edit', [
            'user' => $user,
            'profil' => $profil,
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $rules = [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|valid_email',
            'taille' => 'required|decimal',
            'poids' => 'required|decimal',
            'poids_souhaite' => 'required|decimal',
            'activite' => 'required|in_list[Faible,Moderee,Intense]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/profil/edit')->withInput()->with('error', 'Données invalides');
        }

        // Update user data
        $this->userModel->update($userId, [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
        ]);

        // Recalculate IMC
        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');
        $poids_souhaite = (float) $this->request->getPost('poids_souhaite');
        $imc = $taille > 0 ? round($poids / ($taille * $taille), 2) : 0;

        // Update profil santé
        $profil = $this->profilSanteModel->where('users_id', $userId)->first();
        if ($profil) {
            $this->profilSanteModel->update($profil['id'], [
                'taille' => $taille,
                'poids' => $poids,
                'poids_souhaite' => $poids_souhaite,
                'imc' => $imc,
                'activite' => $this->request->getPost('activite'),
            ]);
        }

        // Update session
        session()->set('poids_souhaite', $poids_souhaite);
        session()->set('activite', $this->request->getPost('activite'));

        return redirect()->to('/profil')->with('success', 'Profil mis à jour avec succès');
    }
}
