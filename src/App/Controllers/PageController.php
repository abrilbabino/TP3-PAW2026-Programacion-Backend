<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class PageController extends Controller
{
    public function index()
    {
        $titulo = htmlspecialchars($_GET["nombre"] ?? "Inicio-PawPrints");
        echo $this->twig->render('index.html.twig', [
            'titulo' => $titulo,
        ]);
    }

    public function sobreNosotros()
    {
        echo $this->twig->render('sobreNosotros.html.twig');
    }

    public function reservaExitosa()
    {
        echo $this->twig->render('reserva-exitosa.html.twig');
    }
    
    public function agregarCarrito(){
        
    }
}