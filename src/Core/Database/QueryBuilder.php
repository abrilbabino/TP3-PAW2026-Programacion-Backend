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

    public function select($table, $params = []){
        $where = "1=1";
        if(isset($params['id'])){
		    $where = "id = :id";
	    }
        $query = "select * from {$table} where {$where}";
        $sentencia = $this->pdo->prepare($query);
        if(isset($params['id'])){
		    $sentencia->bindValue(":id", $params['id']);
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

    public function count($table){
        $query = "select count(*) as total from {$table}";
        $sentencia = $this->pdo->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetch();
    }
}
