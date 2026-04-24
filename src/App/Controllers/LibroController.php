<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\Autor;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;
    private const POR_PAGINA = 6;


    public function catalogo()
    {
        global $request;
        $menu    = $this->menu;
        $redes   = $this->redes;
        $filtros = $this->getFiltros();
        $page    = $request->paginaActual();
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        $resultado  = $this->model->getPaginated($filtros, $page, self::POR_PAGINA);
        $libros     = $resultado['items'];
        $pagination = $resultado['pagination'];
 
        require $this->viewsDir . '/catalogo.view.php';
    }

    private function getFiltros()
    {
        global $request;
        return[
            'genero'    => $request->get('genero'),
            'idioma'    => $request->get('idioma'),
            'autor_id'  => $request->get('autor'),
            'editorial' => $request->get('editorial'),
            'precio_min' => $request->get('precio_min'),
            'precio_max' => $request->get('precio_max'),
            ];
        
    }

    public function csv()
    {
        global $request;
        $filtros = $this->getFiltros();
        $termino = $request->get('busqueda');
 
        $libros = $termino
            ? $this->model->buscar($termino)
            : $this->model->getAll($filtros);
 
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=catalogo-libros.csv');
 
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Título', 'Descripción', 'Género', 'Editorial', 'Idioma', 'Precio', 'Autor'], ',', '"', '\\');
 
        $autorModel = new Autor();
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
 
        foreach ($libros as $libro) {
            $autorModel->load($libro->fields['autor_id']);
            $nombreAutor = $autorModel->fields['nombre'] ?? 'Desconocido';
 
            fputcsv($output, [
                $libro->fields['id'],
                $libro->fields['titulo'],
                $libro->fields['descripcion'],
                $libro->fields['genero'],
                $libro->fields['editorial'],
                $libro->fields['idioma'],
                $libro->fields['precio'],
                $nombreAutor,
            ], ',', '"', '\\');
        }
 
        fclose($output);
    }


    public function detalle()
    {
        global $request;
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $request->get('id');
        $libro = $this->model->get($id);
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        $filtros = [
            'genero'   => $libro->fields['genero'],
            'autor_id' => $libro->fields['autor_id'],
        ];
 
        $relacionados = $this->model->getRelations($filtros);
 
        require $this->viewsDir . '/libro.view.php';
    }

    public function buscar()
    {
        global $request;
        $termino = trim($request->get('busqueda') ?? '');
 
        if (empty($termino)) {
            header('Location: /catalogo');
            return;
        }
 
        $menu  = $this->menu;
        $redes = $this->redes;
        $page  = $request->paginaActual();
 
        $resultado  = $this->model->buscarPaginated($termino, $page, self::POR_PAGINA);
        $libros     = $resultado['items'];
        $pagination = $resultado['pagination'];
 
        $autorModel = new AutorCollection;
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();
 
        require $this->viewsDir . '/catalogo.view.php';
    }
}