<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\Autor;
use Paw\App\Models\EditorialCollection;
use Paw\App\Models\GeneroCollection;
use Paw\App\Models\IdiomaCollection;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function catalogo()
    {
        $request= $this->request;
        $menu = $this->menu;
        $redes = $this->redes;
        $filtros = $this-> getFiltros();

        $generoModel = new GeneroCollection;
        $generoModel->setQueryBuilder($this->model->getQueryBuilder());
        $generos = $generoModel->getAll();

        $editorialModel = new EditorialCollection;
        $editorialModel->setQueryBuilder($this->model->getQueryBuilder());
        $editoriales = $editorialModel->getAll();

        $idiomaModel = new IdiomaCollection;
        $idiomaModel->setQueryBuilder($this->model->getQueryBuilder());
        $idiomas = $idiomaModel->getAll();

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        $datosPaginacion = $this->getDatosPaginacion();
        extract($datosPaginacion);

        $libros = $this->model->getAll($filtros);

        $totalLibros = $this->model->count($filtros);
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        require $this->viewsDir . '/catalogo.view.php';
    }

    private function getFiltros()
    {
        $request = $this->request;
        return[
            'genero_id'    => $request->get('genero'),
            'idioma_id'    => $request->get('idioma'),
            'autor_id'  => $request->get('autor'),
            'editorial_id' => $request->get('editorial'),
            'precio_min' => $request->get('precio_min'),
            'precio_max' => $request->get('precio_max'),
            ];
        
    }

    public function csv()
    {
        $filtros = $this->getFiltros();
        $termino = $_GET['busqueda'] ?? null;

        if ($termino) {
            $todosLosLibros = $this->model->buscar($termino);
        } else {
            $todosLosLibros= $this->model->getAll($filtros);
        }

        $datosPaginacion = $this->getDatosPaginacion();
        extract($datosPaginacion);;

        $libros = array_slice($todosLosLibros, $inicio, $librosPorPagina);
        
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
                $nombreAutor
            ], ',', '"', '\\');
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
        $request = $this->request;
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $request->get('id');
        $libro = $this->model->get($id);

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autor = $autorModel->get($libro->fields['autor_id']);

        $filtros= [
            'genero'    => $libro->fields['genero'],
            'autor_id'  => $libro->fields['autor_id'],
        ];
        
        $relacionados = $this->model->getRelations($filtros); 

        require $this->viewsDir . '/libro.view.php';
    }

    public function buscar()
    {
        $request = $this->request;
        $termino = trim($request->get('busqueda') ?? '');
        
        if (empty($termino)) {
            header('Location: /catalogo');
            return;
        }

        $menu = $this->menu;
        $redes = $this->redes;

        $todosLosLibrosEncontrados = $this->model->buscar($termino);

        $datosPaginacion = $this->getDatosPaginacion();
        extract($datosPaginacion);

        $libros = array_slice($todosLosLibrosEncontrados, $inicio, $librosPorPagina);

        $totalLibros = count($todosLosLibrosEncontrados);
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();


        require $this->viewsDir . '/catalogo.view.php';
    }
}