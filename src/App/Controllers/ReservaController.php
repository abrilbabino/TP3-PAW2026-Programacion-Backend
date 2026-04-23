<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Reserva;
use Paw\Core\Controller;
use Paw\Core\MailService;

class ReservaController extends Controller{

    public ?string $modelName = Reserva::class;

    public function reserva()
    {
        $menu = $this->menu;
        $redes = $this->redes;
        require $this->viewsDir . '/reserva.view.php';
    }

    public function procesarReserva()
    {
        global $request;
        global $config;
        $menu = $this->menu;
        $redes = $this->redes;

        $this->model->set($request->post());

        $errores = $this->model->validar();

        if(count($errores) > 0){
            require $this->viewsDir . '/reserva.view.php';
        }
        else{
            $destinatario = $config->get('MAIL_PERSONAL'); 
            $mailService = new MailService;
            $mailService->enviarConfirmacionReserva($destinatario, $this->model->fields);
            header("Location: /reserva-exitosa");
        }
    }
}