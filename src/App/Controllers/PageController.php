<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class PageController extends Controller
{
    public function index()
    {
        $titulo = htmlspecialchars($_GET["nombre"] ?? "PAW");
        require $this -> viewsDir . 'not-found.view.php';
    }
    /**ver si agregamos las otras function */
}