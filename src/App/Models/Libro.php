<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Exceptions\InvalidValueFormatException;

class Libro extends Model
{
    public $table = 'libro';

    public $fields = [
        "id" => null,
        "imagen" => null,
        "titulo" => null,
        "descripcion" => null,
        "precio" => null,
        "genero_id" => null,
        "editorial_id" => null,
        "idioma_id" => null,
        "stock" => null,
        "autor_id" => null,
    ];

    public function setId($id){
        if($id < 0 || !is_numeric($id)){
            throw new InvalidValueFormatException("El ID del libro debe ser un entero mayor a 0");
        }
        $this->fields["id"] = $id;
    }

    public function setImagen(string $imagen){
        $this->fields["imagen"] = $imagen ?? 'portada.png';
    }

    public function setTitulo(string $titulo)
    {
        if (strlen($titulo) > 60) {
            throw new InvalidValueFormatException("El titulo no debe ser mayor a 60 caracteres");
        }
        if (strlen($titulo) < 1) {
            throw new InvalidValueFormatException("El titulo es obligatorio");
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
            throw new InvalidValueFormatException("El precio no puede ser negativo");
        }
        $this->fields["precio"] = $precio;
    }

     public function setGenero_Id(int $generoId)
    {
        if ($generoId < 1) {
            throw new InvalidValueFormatException("El ID del género debe ser mayor a 0");
        }
        $this->fields["genero_id"] = $generoId;
    }

     public function setEditorial_Id(int $editorialId)
    {
        if ($editorialId < 1) {
            throw new InvalidValueFormatException("El ID de la editorial debe ser mayor a 0");
        }
        $this->fields["editorial_id"] = $editorialId;
    }

     public function setIdioma_Id(int $idiomaId)
    {
        if ($idiomaId < 1) {
            throw new InvalidValueFormatException("El ID del idioma debe ser mayor a 0");
        }
        $this->fields["idioma_id"] = $idiomaId;
    }

    public function setStock(int $stock)
    {
        if ($stock < 0) {
            throw new InvalidValueFormatException("El stock no puede ser negativo");
        }
        $this->fields["stock"] = $stock;
    }

    public function setAutor_Id(int $autorId)
    {
        if ($autorId < 1 || !is_numeric($autorId)) {
            throw new InvalidValueFormatException("El ID del autor debe ser un entero mayor a 0");
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

    public function load($id){
        if(!is_numeric($id)||$id < 0){
            throw new InvalidValueFormatException("El ID del libro debe ser un entero mayor a 0");
        }
        $params = ["id" => $id];
        $record = current($this->queryBuilder->select($this->table, $params));
        $this->set($record);
    }
}
