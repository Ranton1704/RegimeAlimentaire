<?php

namespace App\Models;

use CodeIgniter\Model;

class PortefeuilleTransactionModel extends Model
{
    protected $table = 'portefeuille_transactions';
    protected $allowedFields = [
        'user_id', 'regime_id', 'type', 'montant', 'description', 'created_at'
    ];
}
