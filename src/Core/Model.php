<?php

namespace Paw\Core;

use Paw\Core\Database\QueryBuilder;
use Paw\Core\Traits\Loggable;

class Model
{
    protected $queryBuilder;
    use Loggable;

    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->queryBuilder = $qb;
    }

    public function getQueryBuilder(){
        return $this->queryBuilder;
    }

    public function findBy(array $params): array {
        return $this->queryBuilder->select($this->table, $params);
    }

    public function save(): int {
        // Filtramos los campos que son nulos para no interferir con valores por defecto o IDs autoincrementables
        $data = array_filter($this->fields, function($val) {
            return $val !== null;
        });
        return $this->queryBuilder->insert($this->table, $data);
    }
}
