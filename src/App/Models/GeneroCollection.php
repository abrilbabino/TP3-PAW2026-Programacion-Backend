<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class GeneroCollection extends Model
{
    public $table = 'genero';

    public function getAll()
    {
        $generos = $this->queryBuilder->select($this->table);
        $generoCollection = [];
        foreach ($generos as $genero) {
            $newGenero = new Genero;
            $newGenero->set($genero);
            $generoCollection[] = $newGenero;
        }
        return $generoCollection;
    }
}