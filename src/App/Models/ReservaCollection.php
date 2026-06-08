<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class ReservaCollection extends Model
{
    public $table = 'reservas';

    public function getAll(): array
    {
        $reservas = $this->queryBuilder->select($this->table);
        usort($reservas, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });
        return $reservas;
    }
}
