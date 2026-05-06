<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // protection déjà faite par le filter auth
        return view('dashboard/index');
    }
}