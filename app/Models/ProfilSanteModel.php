<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilSanteModel extends Model
{
    protected $table = 'profil_sante';
    protected $allowedFields = [
        'users_id',
        'poids',
        'taille',
        'age',
        'imc',
    ];
}
