<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class ErrorController extends Controller
{

    public function notFaund()
    {
        http_response_code(404);
        $titulo = 'Pagina encontrada';
        require $this -> viewsDir . 'not-founs.view.php';
    }
    public function internalError()
    {
        http_response_code(500);
        $titulo = "Error interno del servidor";
        require $this->viewsDir . '/internal_error.view.php';
    }

}