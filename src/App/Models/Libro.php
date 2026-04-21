<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Exception;

class Libro extends Model
{
    public $table = 'libro';

    public $fields = [
        "titulo" => null,
        "descripcion" => null,
        "precio" => null,
        "genero" => null,
        "editorial" => null,
        "idioma" => null,
        "stock" => null,
        "autor_id" => null,
    ];

    public function setTitulo(string $titulo)
    {
        if (strlen($titulo) > 60) {
            throw new Exception("El titulo no debe ser mayor a 60 caracteres");
        }
        if (strlen($titulo) < 1) {
            throw new Exception("El titulo es obligatorio");
        }
        $this->fields["titulo"] = $titulo;
    }

    public function setDescripcion(string $descripcion)
    {
        $this->fields["descripcion"] = $descripcion;
    }

    public function setPrecio(float $precio)
    {
        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo");
        }
        $this->fields["precio"] = $precio;
    }

    public function setGenero(string $genero)
    {
        if (strlen($genero) > 60) {
            throw new Exception("El genero no debe ser mayor a 60 caracteres");
        }
        if (strlen($genero) < 1) {
            throw new Exception("El genero es obligatorio");
        }
        $this->fields["genero"] = $genero;
    }

    public function setEditorial(string $editorial)
    {
        if (strlen($editorial) > 60) {
            throw new Exception("La editorial no debe ser mayor a 60 caracteres");
        }
        $this->fields["editorial"] = $editorial;
    }

    public function setIdioma(string $idioma)
    {
        if (strlen($idioma) > 60) {
            throw new Exception("El idioma no debe ser mayor a 60 caracteres");
        }
        $this->fields["idioma"] = $idioma;
    }

    public function setStock(int $stock)
    {
        if ($stock < 0) {
            throw new Exception("El stock no puede ser negativo");
        }
        $this->fields["stock"] = $stock;
    }

    public function setAutorId(int $autorId)
    {
        if ($autorId < 1) {
            throw new Exception("El ID del autor debe ser mayor a 0");
        }
        $this->fields["autor_id"] = $autorId;
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
