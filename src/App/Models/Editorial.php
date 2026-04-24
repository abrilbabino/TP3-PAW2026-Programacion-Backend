<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Editorial extends Model
{
    public $table = 'editorial';

    public $fields = [
        "id" => null,
        "nombre" => null,
    ];

    public function setId($id){
        $this->fields["id"] = $id;
    }

    public function setNombre(string $nombre)
    {
        if (strlen($nombre) > 100) {
            throw new InvalidValueFormatException("El nombre no debe ser mayor a 100 caracteres");
        }
        if (strlen($nombre) < 1) {
            throw new InvalidValueFormatException("El nombre es obligatorio");
        }
        $this->fields["nombre"] = $nombre;
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