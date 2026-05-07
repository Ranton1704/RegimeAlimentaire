<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametreModel extends Model
{
    protected $table = 'parametres';
    protected $allowedFields = ['cle', 'valeur'];

    public function getValue(string $key, $default = null)
    {
        $row = $this->where('cle', $key)->first();

        return $row['valeur'] ?? $default;
    }

    public function setValue(string $key, $value): bool
    {
        $existing = $this->where('cle', $key)->first();
        $payload = [
            'cle' => $key,
            'valeur' => (string) $value,
        ];

        if ($existing) {
            return (bool) $this->update($existing['id'], $payload);
        }

        return (bool) $this->insert($payload);
    }
}
