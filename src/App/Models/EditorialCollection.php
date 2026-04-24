<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class EditorialCollection extends Model
{
    public $table = 'editorial';

    public function getAll()
    {
        $editoriales = $this->queryBuilder->select($this->table);
        $editorialCollection = [];
        foreach ($editoriales as $editorial) {
            $newEditorial = new Editorial;
            $newEditorial->set($editorial);
            $editorialCollection[] = $newEditorial;
        }
        return $editorialCollection;
    }
}