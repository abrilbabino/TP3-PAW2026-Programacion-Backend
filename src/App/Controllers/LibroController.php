<?php

namespace Paw\App\Controllers;

use Paw\App\Models\AutorCollection;
use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;

class LibroController extends Controller
{
    public ?string $modelName = LibroCollection::class;
    public function catalogo()
    {
        global $request;
        $menu = $this->menu;
        $redes = $this->redes;
        $libros = $this->model->getAll();

        $autorModel = new AutorCollection; 
        $autorModel->setQueryBuilder($this->model->getQueryBuilder());
        $autores = $autorModel->getAll();

        $pagina = $request->paginaActual();
        $librosPorPagina = 6;

        $inicio = ($pagina - 1) * $librosPorPagina;
        $fin = $inicio + $librosPorPagina;

        $totalLibros = $this->model->getQueryBuilder()->count('libro')['total'];
        $totalPaginas = ceil($totalLibros / $librosPorPagina);

        require $this->viewsDir . '/catalogo.view.php';
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
}