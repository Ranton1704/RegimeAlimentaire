<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeUserModel extends Model
{
    protected $table = 'regimes_users';
    protected $allowedFields = [
        'users_id',
        'regime_id',
        'date_debut',
        'date_fin',
    ];
}
