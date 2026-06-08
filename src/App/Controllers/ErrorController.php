<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;

class ErrorController extends Controller
{

    public function notFound()
    {
        http_response_code(404);
        echo $this->twig->render('not-found.html.twig');
    }
    public function internalError($e = null)
    {
        http_response_code(500);
        echo $this->twig->render('internal-error.html.twig');
    }
    public function invalidFormat($e){
        http_response_code(400);
        echo $this->twig->render('invalid_format.html.twig');
    }
    public function libroNotFound($e){
        http_response_code(404);
        echo $this->twig->render('libro-not-found.html.twig');
    }

}