<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class LibroController extends Controller
{

    public function detalle()
    {
        $menu  = $this->menu;
        $redes = $this->redes;
        $id    = $_GET['id'] ?? null;
        $libro = [];

        require $this->viewsDir . '/libro.view.php';
    }
}