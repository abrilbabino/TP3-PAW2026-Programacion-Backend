<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class LibroCollection extends Model
{
    public $table = 'libro';

    public function getAll(array $filtros = [])
{
    // Eliminamos los filtros vacíos que vienen del GET
    $params = array_filter($filtros, fn($v) => $v !== null && $v !== '');

    $libros = $this->queryBuilder->select($this->table, $params);

    $librosCollection = [];
    foreach ($libros as $libro) {
        $newLibro = new Libro;
        $newLibro->set($libro);
        $librosCollection[] = $newLibro;
    }
    return $librosCollection;
}

public function count(array $filtros = [])
{
    $params = array_filter($filtros, fn($v) => $v !== null && $v !== '');
    return $this->queryBuilder->count($this->table, $params)['total'];
}

    public function get($id){
        $libro = new Libro;
        $libro -> setQueryBuilder($this->queryBuilder);
        $libro->load($id);
        return $libro;	
    }
}