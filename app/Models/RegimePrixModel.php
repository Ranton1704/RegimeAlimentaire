<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimePrixModel extends Model
{
    protected $table = 'regimes_prix';
    protected $allowedFields = [
        'regime_id', 'prix', 'duree_jours'
    ];
}
