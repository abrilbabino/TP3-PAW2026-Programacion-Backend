<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class IdiomaCollection extends Model
{
    public $table = 'idioma';

    public function getAll()
    {
        $idiomas = $this->queryBuilder->select($this->table);
        $idiomaCollection = [];
        foreach ($idiomas as $idioma) {
            $newIdioma = new Idioma;
            $newIdioma->set($idioma);
            $idiomaCollection[] = $newIdioma;
        }
        return $idiomaCollection;
    }
}