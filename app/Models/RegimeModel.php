<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';
    protected $allowedFields = [
        'nom', 'description', 'objectifs_id', 'variation_poids',
        'pourcentage_viande', 'pourcentage_poisson', 'pourcentage_volaille'
    ];
}
