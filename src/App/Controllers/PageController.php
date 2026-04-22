<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class PageController extends Controller
{
    public function index()
    {
        $titulo = htmlspecialchars($_GET["nombre"] ?? "Inicio-PawPrints");
        $menu = $this->menu;
        $redes = $this->redes;
        require $this -> viewsDir . '/index.view.php';
    }

    public function reserva()
    {
        $menu = $this->menu;
        $redes = $this->redes;
        require $this->viewsDir . '/reserva.view.php';
    }

    public function sobreNosotros()
    {
        $menu = $this->menu;
        $redes = $this->redes;
        require $this->viewsDir . '/sobreNosotros.view.php';
    }
}