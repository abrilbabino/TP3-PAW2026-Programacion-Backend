<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\Core\Pagination;


class LibroCollection extends Model
{
    public $table = 'libro';

    public function getAll(array $filtros = [])
    {
        [$params, $precios] = $this->extraerFiltros($filtros);
        $libros = $this->queryBuilder->select($this->table, $params, $precios);
        return $this->mapearLibros($libros);
    }
 

    public function getRelations(array $filtros = [])
    {
        $libros = $this->queryBuilder->selectRelated($this->table, $filtros);
        return $this->mapearLibros($libros);
    }



    public function count(array $filtros = [])
    {
        [$params, $precios] = $this->extraerFiltros($filtros);
        return $this->queryBuilder->count($this->table, $params, $precios)['total'];
    }

    

    public function get($id){
        $libro = new Libro;
        $libro -> setQueryBuilder($this->queryBuilder);
        $libro->load($id);
        return $libro;	
    }

    public function buscar($termino)
    {
        $libros = $this->queryBuilder->buscar($termino);
        return $this->mapearLibros($libros);
    }


    public function getPaginated(array $filtros, int $page, int $perPage = 6): array
    {
        [$params, $precios] = $this->extraerFiltros($filtros);
 
        $total      = (int) $this->queryBuilder->count($this->table, $params, $precios)['total'];
        $pagination = new Pagination($page, $perPage, $total);
 
        $libros = $this->queryBuilder->select(
            $this->table,
            $params,
            $precios,
            $pagination->perPage,
            $pagination->offset
        );
 
        return [
            'items'      => $this->mapearLibros($libros),
            'pagination' => $pagination,
        ];
    }

    public function buscarPaginated(string $termino, int $page, int $perPage = 6): array
    {
        $total      = $this->queryBuilder->buscarCount($termino);
        $pagination = new Pagination($page, $perPage, $total);
 
        $libros = $this->queryBuilder->buscar($termino, $pagination->perPage, $pagination->offset);
 
        return [
            'items'      => $this->mapearLibros($libros),
            'pagination' => $pagination,
        ];
    }


    // --- Helpers privados ---
 
    private function extraerFiltros(array $filtros): array
    {
        $precios = [
            'min' => $filtros['precio_min'] ?? null,
            'max' => $filtros['precio_max'] ?? null,
        ];
        unset($filtros['precio_min'], $filtros['precio_max']);
        $params = array_filter($filtros, fn($v) => $v !== null && $v !== '');
        return [$params, $precios];
    }
 
    private function mapearLibros(array $rows): array
    {
        $coleccion = [];
        foreach ($rows as $row) {
            $libro = new Libro;
            $libro->set($row);
            $coleccion[] = $libro;
        }
        return $coleccion;
    }
}