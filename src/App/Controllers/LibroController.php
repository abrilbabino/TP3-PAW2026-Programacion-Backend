<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\Autor;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function catalogo()
    {
        global $request;
        $menu = $this->menu;
        $redes = $this->redes;
        $filtros = [
            'genero'    => $request->get('genero'),
            'idioma'    => $request->get('idioma'),
            'autor_id'  => $request->get('autor'),
            'editorial' => $request->get('editorial'),
            ];

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        $paginacion = $this->getDatosPaginacion();
        $pagina = $paginacion['pagina'];
        $librosPorPagina = $paginacion['librosPorPagina'];
        $inicio = $paginacion['inicio'];
        $fin = $paginacion['fin'];

        $libros = $this->model->getAll($filtros);

        $totalLibros = $this->model->count($filtros);
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        require $this->viewsDir . '/catalogo.view.php';
    }

    public function csv()
    {
        $todosLosLibros = $this->model->getAll();
    
        $paginacion = $this->getDatosPaginacion();
        $inicio = $paginacion['inicio'];
        $librosPorPagina = $paginacion['librosPorPagina'];

        $libros = array_slice($todosLosLibros, $inicio, $librosPorPagina);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=catalogo-libros.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Título', 'Descripción', 'Género', 'Editorial', 'Idioma', 'Precio', 'Autor']);

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
                $nombreAutor
            ]);
        }
        fclose($output);
    }

    private function getDatosPaginacion()
    {
        global $request;
        $pagina = $request->paginaActual();
        $librosPorPagina = 6; 
        $inicio = ($pagina - 1) * $librosPorPagina;
        $fin = $inicio + $librosPorPagina;

        return [
            'pagina' => $pagina,
            'librosPorPagina' => $librosPorPagina,
            'inicio' => $inicio,
            'fin' => $fin
        ];
    }

    public function detalle()
    {
        global $request;
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $request->get('id');
        $libro = $this->model->get($id);

        require $this->viewsDir . '/libro.view.php';
    }
    public function buscar()
    {
        $termino = trim($_GET['busqueda'] ?? '');
        
        if (empty($termino)) {
            header('Location: /catalogo');
            return;
        }

        $menu = $this->menu;
        $redes = $this->redes;

        $todosLosLibrosEncontrados = $this->model->buscar($termino);

        $paginacion = $this->getDatosPaginacion();
        $pagina = $paginacion['pagina'];
        $librosPorPagina = $paginacion['librosPorPagina'];
        $inicio = $paginacion['inicio'];
        $fin = $paginacion['fin'];

        $libros = array_slice($todosLosLibrosEncontrados, $inicio, $librosPorPagina);

        $totalLibros = count($todosLosLibrosEncontrados);
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        // se cargamos autores pq se puede necesitar para los filtros
        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        require $this->viewsDir . '/catalogo.view.php';
    }
}