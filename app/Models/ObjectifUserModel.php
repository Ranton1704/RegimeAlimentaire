<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifUserModel extends Model
{
    protected $table = 'objectifs_users';
    protected $allowedFields = [
        'users_id',
        'objectifs_id',
    ];
}
