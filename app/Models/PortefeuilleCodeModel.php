<?php

namespace App\Models;

use CodeIgniter\Model;

class PortefeuilleCodeModel extends Model
{
    protected $table = 'portfeuille_code';
    protected $allowedFields = [
        'code', 'description', 'utilise_le', 'used_by', 'utilise', 'montant'
    ];
}
