<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
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
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'genre' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register_step1', [
                'validation' => $this->validator
            ]);
        }

        // stocker temporairement
        session()->set('step1', $this->request->getPost());

        return redirect()->to('/register-step2');
    }

    // STEP 2
    public function registerStep2()
    {
        if (!session()->get('step1')) {
            return redirect()->to('/register-step1');
        }

        return view('auth/register_step2');
    }

    public function postStep2()
    {
        helper(['form']);

        $rules = [
            'taille' => 'required|decimal',
            'poids' => 'required|decimal'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register_step2', [
                'validation' => $this->validator
            ]);
        }

        $userModel = new UserModel();

        $step1 = session()->get('step1');
        $step2 = $this->request->getPost();

        $data = array_merge($step1, $step2);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $userModel->save($data);

        session()->remove('step1');

        return redirect()->to('/login')->with('success', 'Compte créé');
    }

    // LOGIN
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $userModel = new UserModel();

        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->with('error', 'Login incorrect');
        }

        session()->set([
            'user_id' => $user['id'],
            'isLoggedIn' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}