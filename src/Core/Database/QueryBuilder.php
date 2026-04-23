<?php

namespace Paw\Core\Database;

use PDO;
use Monolog\Logger;

class QueryBuilder
{
    private $pdo;
    private $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function select($table, $params = [], $precios = [])
    {
        $conditions = [];
        $binds = [];

        foreach ($params as $campo => $valor) {
            $conditions[] = "{$campo} = :{$campo}";
            $binds[":{$campo}"] = $valor;
        }

        if (!empty($precios['min'])) {
            $conditions[] = "precio >= :pmin";
            $binds[":pmin"] = $precios['min'];
        }
        if (!empty($precios['max'])) {
            $conditions[] = "precio <= :pmax";
            $binds[":pmax"] = $precios['max'];
        }

        $where = !empty($conditions) ? implode(" AND ", $conditions) : "1=1";
        $query = "SELECT * FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);
        foreach ($binds as $key => $val) {
            $sentencia->bindValue($key, $val);
        }
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectRelated($table, $params = [])
    {
        $conditions = [];
        $binds = [];

        foreach ($params as $campo => $valor) {
            $conditions[] = "{$campo} = :{$campo}";
            $binds[":{$campo}"] = $valor;
        }

        $where = !empty($conditions) ? implode(" OR ", $conditions) : "1=1";
        $query = "SELECT * FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);
        foreach ($binds as $key => $val) {
            $sentencia->bindValue($key, $val);
        }
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(){
    }

    public function update(){
    }

    public function delete(){
    }

    public function count($table, $params = [])
    {
        $conditions = [];

        foreach ($params as $campo => $valor) {
            $conditions[] = "{$campo} = :{$campo}";
        }

        $where = !empty($conditions) ? implode(" AND ", $conditions) : "1=1";
        $query = "SELECT COUNT(*) as total FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);

        foreach ($params as $campo => $valor) {
            $sentencia->bindValue(":{$campo}", $valor);
        }

        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetch();
    }

    public function buscar($termino){
        $query = "SELECT * FROM libro WHERE titulo LIKE :termino OR descripcion LIKE :termino";
        $sentencia = $this->pdo->prepare($query);
        $sentencia->bindValue(':termino', "%{$termino}%");
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

}
