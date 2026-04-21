<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class AutorCollection extends Model
{
    public $table = 'autor';

    public function getAll()
    {
        $autores = $this->queryBuilder->select($this->table);
        $autoresCollection = [];
        foreach ($autores as $autor) {
            $newAutor = new Autor;
            $newAutor->set($autor);
            $autoresCollection[] = $newAutor;
        }
        return $autoresCollection;
    }
}