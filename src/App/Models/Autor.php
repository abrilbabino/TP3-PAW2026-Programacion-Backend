<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Exception;

class Autor extends Model
{
    public $table = 'autor';

    public $fields = [
        "nombre" => null,
        "biografia" => null,
    ];

    public function setNombre(string $nombre)
    {
        if (strlen($nombre) > 100) {
            throw new Exception("El nombre no debe ser mayor a 100 caracteres");
        }
        if (strlen($nombre) < 1) {
            throw new Exception("El nombre es obligatorio");
        }
        $this->fields["nombre"] = $nombre;
    }

    public function setBiografia(string $biografia)
    {
        $this->fields["biografia"] = $biografia;
    }

    public function set(array $values)
    {
        foreach (array_keys($this->fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this->$method($values[$field]);
        }
    }
}