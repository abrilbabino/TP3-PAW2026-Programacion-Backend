<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class LibroCollection extends Model
{
    public $table = 'libro';

    public function getAll(array $filtros = [])
    {
        $precios = [
            'min' => $filtros['precio_min'] ?? null,
            'max' => $filtros['precio_max'] ?? null
        ];
        
        unset($filtros['precio_min'], $filtros['precio_max']);
        $params = array_filter($filtros, fn($v) => $v !== null && $v !== '');

        $libros = $this->queryBuilder->select($this->table, $params, $precios);

        $librosCollection = [];
        foreach ($libros as $libro) {
            $newLibro = new Libro;
            $newLibro->set($libro);
            $librosCollection[] = $newLibro;
        }
        return $librosCollection;
    }


    public function count(array $filtros = [])
    {
        $precios = [
            'min' => $filtros['precio_min'] ?? null,
            'max' => $filtros['precio_max'] ?? null
        ];
        
        unset($filtros['precio_min'], $filtros['precio_max']);
        $params = array_filter($filtros, fn($v) => $v !== null && $v !== '');

        return $this->queryBuilder->count($this->table, $params, $precios)['total'];
    }
    

    public function get($id){
        $libro = new Libro;
        $libro -> setQueryBuilder($this->queryBuilder);
        $libro->load($id);
        return $libro;	
    }

    public function buscar($termino){
        $libros = $this->queryBuilder->buscar($termino);
        $librosCollection = [];
        foreach ($libros as $libro) {
            $newLibro = new Libro;
            $newLibro->set($libro);
            $librosCollection[] = $newLibro;
        }
        return $librosCollection;
    }
}