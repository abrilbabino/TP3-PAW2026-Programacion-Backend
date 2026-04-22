<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class LibroCollection extends Model
{
    public $table = 'libro';

    public function getAll()
    {
        $libros = $this->queryBuilder->select($this->table);
        $librosCollection = [];
        foreach ($libros as $libro) {
            $newLibro = new Libro;
            $newLibro->set($libro);
            $librosCollection[] = $newLibro;
        }
        return $librosCollection;
    }

    public function get($id){
        $libro = new Libro;
        $libro -> setQueryBuilder($this->queryBuilder);
        $libro->load($id);
        return $libro;	
    }
}