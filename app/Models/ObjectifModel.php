<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table = 'objectifs';
    protected $allowedFields = [
        'nom',
        'description',
    ];
}
