<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class LibroController extends Controller
{
    public function catalogo()
    {
        $menu = $this->menu;
        $redes = $this->redes;

        $libros = [];
        $autores     = [];
        $totalPaginas = 1;
        $pagina      = $_GET['pagina'] ?? 1;

        require $this->viewsDir . '/catalogo.view.php';
    }

    public function detalle()
    {
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $_GET['id'] ?? null;
        $libro = [];

        require $this->viewsDir . '/libro.view.php';
    }
}