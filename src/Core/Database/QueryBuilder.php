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

    public function select($table, $params = [])
    {
        $conditions = [];

        foreach ($params as $campo => $valor) {
            $conditions[] = "{$campo} = :{$campo}";
        }
        $where = !empty($conditions) ? implode(" AND ", $conditions) : "1=1";
        $query = "SELECT * FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);

        foreach ($params as $campo => $valor) {
            $sentencia->bindValue(":{$campo}", $valor);
        }

        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
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
}
