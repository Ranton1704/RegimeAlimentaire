<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportiveModel extends Model
{
    protected $table = 'activite_sportive';
    protected $allowedFields = [
        'nom',
        'description',
        'objectifs_id',
        'duree_minutes',
    ];
}
