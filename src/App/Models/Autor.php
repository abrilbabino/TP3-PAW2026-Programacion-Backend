<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Autor extends Model
{
    public $table = 'autor';

    public $fields = [
        "id" => null,
        "nombre" => null,
        "biografia" => null,
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

    public function load($id){
        $params = ["id" => $id];
        $record = current($this->queryBuilder->select($this->table, $params));
        $this->set($record);
    }
}