<?php

namespace Paw\Core\Database;

use PDO;
use PDOStatement;
use Monolog\Logger;
use InvalidArgumentException;

class QueryBuilder
{
    private PDO $pdo;
    private Logger $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function select(string $table, array $params = [], array $precios = [], int $limit = 0, int $offset = 0): array
    {
        [$where, $binds] = $this->buildWhere($params, 'AND', $precios);
        $query = "SELECT * FROM {$table} WHERE {$where}";

        $query = $this->addPagination($query, $limit);
        $sentencia = $this->pdo->prepare($query);
        $this->bindValues($sentencia, $binds);
        $this->bindPagination($sentencia, $limit, $offset);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectRelated(string $table, array $params = []): array
    {
        [$where, $binds] = $this->buildWhere($params, 'OR');
        $query = "SELECT * FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);
        $this->bindValues($sentencia, $binds);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $table, array $params = [], array $precios = []): array
    {
        [$where, $binds] = $this->buildWhere($params, 'AND', $precios);
        $query = "SELECT COUNT(*) as total FROM {$table} WHERE {$where}";

        $sentencia = $this->pdo->prepare($query);
        $this->bindValues($sentencia, $binds);
        $sentencia->execute();

        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public function buscar(string $termino, int $limit = 0, int $offset = 0): array
    {
        $query = "SELECT * FROM libro WHERE titulo LIKE :termino OR descripcion LIKE :termino";
        $query = $this->addPagination($query, $limit);

        $sentencia = $this->pdo->prepare($query);
        $sentencia->bindValue(':termino', "%{$termino}%");
        $this->bindPagination($sentencia, $limit, $offset);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarCount(string $termino): int
    {
        $query = "SELECT COUNT(*) as total FROM libro WHERE titulo LIKE :termino OR descripcion LIKE :termino";
        $sentencia = $this->pdo->prepare($query);
        $sentencia->bindValue(':termino', "%{$termino}%");
        $sentencia->execute();
        return (int) $sentencia->fetchColumn();
    }

    private function buildWhere(array $params, string $operator = 'AND', array $precios = []): array
    {
        $conditions = [];
        $binds = [];

        foreach ($params as $campo => $valor) {
            
            $conditions[] = "{$campo} = :{$campo}";
            $binds[":{$campo}"] = $valor;
        }

        if (isset($precios['min']) && $precios['min'] !== '') {
            $conditions[] = "precio >= :pmin";
            $binds[':pmin'] = $precios['min'];
        }

        if (isset($precios['max']) && $precios['max'] !== '') {
            $conditions[] = "precio <= :pmax";
            $binds[':pmax'] = $precios['max'];
        }

        $where = !empty($conditions) ? implode(" {$operator} ", $conditions) : "1=1";

        return [$where, $binds];
    }

    private function bindValues(PDOStatement $sentencia, array $binds): void
    {
        foreach ($binds as $key => $val) {
            $sentencia->bindValue($key, $val);
        }
    }

    private function addPagination(string $query, int $limit): string
    {
        if ($limit <= 0) {
            return $query;
        }

        return "{$query} LIMIT :limit OFFSET :offset";
    }

    private function bindPagination(PDOStatement $sentencia, int $limit, int $offset): void
    {
        if ($limit <= 0) {
            return;
        }

        $sentencia->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sentencia->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

}
