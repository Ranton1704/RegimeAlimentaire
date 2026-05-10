<?php

namespace App\Controllers;

use App\Models\ProfilSanteModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    // LOGIN PAGE
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        return view('auth/login');
    }

    // LOGIN POST
    public function doLogin()
    {
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        $storedPassword = $user['mot_de_passe'] ?? $user['password'] ?? null;

        if (!$user || !$storedPassword) {
            return redirect()->to('/login')->withInput()->with('error', 'Email ou mot de passe incorrect');
        }

        $isValidPassword = password_verify($password, $storedPassword)
            || hash_equals((string) $storedPassword, (string) $password);

        if (!$isValidPassword) {
            return redirect()->to('/login')->withInput()->with('error', 'Email ou mot de passe incorrect');
        }

        if (!password_get_info($storedPassword)['algo']) {
            $userModel->update($user['id'], [
                'mot_de_passe' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }

        if (!isset($user['id'])) {
            return redirect()->to('/')->withInput()->with('error', 'Email ou mot de passe incorrect');
        }

        session()->set([
            'user_id' => $user['id'],
            'role' => strtoupper((string) ($user['role'] ?? 'USER')),
            'isLoggedIn' => true
        ]);

        return redirect()->to('dashboard');
    }

    // LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    // STEP 1
    public function registerStep1()
    {
        return view('auth/register_step1');
    }

    public function postStep1()
    {
        helper(['form']);

        $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'genre' => 'required|in_list[Homme,Femme,Autre]',
            'date_naissance' => 'required|valid_date[Y-m-d]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register-step1')->withInput()->with('error', implode(' | ', $this->validator->getErrors()));
        }

        session()->set('step1', [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'genre' => $this->request->getPost('genre'),
            'date_naissance' => $this->request->getPost('date_naissance'),
        ]);

        return redirect()->to('register-step2');
    }

    // STEP 2
    public function registerStep2()
    {
        if (!session()->get('step1')) {
            return redirect()->to('register-step1');
        }

        return view('auth/register_step2');
    }

    public function postStep2()
    {
        helper(['form']);

        $rules = [
            'taille' => 'required|decimal',
            'poids' => 'required|decimal',
            'poids_souhaite' => 'required|decimal',
            'activite' => 'required|in_list[Faible,Moderee,Intense]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register-step2')->withInput()->with('error', implode(' | ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $profilSanteModel = new ProfilSanteModel();

        // Extract user data (only for users table)
        $step1 = session()->get('step1') ?? [];
        $userData = [
            'nom' => $step1['nom'] ?? '',
            'prenom' => $step1['prenom'] ?? '',
            'email' => $step1['email'] ?? '',
            'genre' => $step1['genre'] ?? '',
            'date_naissance' => $step1['date_naissance'] ?? '',
        ];

        $userData['mot_de_passe'] = password_hash($step1['password'] ?? '', PASSWORD_DEFAULT);

        // Insert user (use insert() instead of save() to avoid conflicts)
        if (!$userModel->insert($userData)) {
            return redirect()->to('/register-step1')->with('error', 'Impossible de créer le compte');
        }

        $userId = $userModel->getInsertID();
        if (!$userId) {
            return redirect()->to('/register-step1')->with('error', 'Impossible de créer le compte');
        }

        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');
        $imc = $taille > 0 ? round($poids / ($taille * $taille), 2) : 0;

        $dateNaissance = $step1['date_naissance'] ?? null;
        $age = 18;

        if (!empty($dateNaissance)) {
            try {
                $birthDate = new \DateTime($dateNaissance);
                $age = (int) $birthDate->diff(new \DateTime('now'))->y;
            } catch (\Throwable $e) {
                $age = 18;
            }
        }

        $profilSanteModel->insert([
            'users_id' => $userId,
            'poids' => $poids,
            'taille' => $taille,
            'poids_souhaite' => (float) $this->request->getPost('poids_souhaite'),
            'age' => $age,
            'imc' => $imc,
            'activite' => $this->request->getPost('activite'),
        ]);

        session()->set('onboarding_health', [
            'poids_souhaite' => (float) $this->request->getPost('poids_souhaite'),
            'activite' => $this->request->getPost('activite'),
            'imc' => $imc,
        ]);

        session()->remove('step1');

        session()->set([
            'user_id' => $userId,
            'role' => 'USER',
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/objectifs')->with('success', 'Compte créé avec succès');
    }
}