<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Reserva;
use Paw\Core\Controller;
use Paw\Core\MailService;

class ReservaController extends Controller{

    public ?string $modelName = Reserva::class;

    public function reserva()
    {
        echo $this->twig->render('reserva.html.twig');
    }

    public function procesarReserva()
    {
        global $config;

        $request = $this->request;
        $menu = $this->menu;
        $redes = $this->redes;

        $this->model->set($request->post());

        $errores = $this->model->validar();

        if(count($errores) > 0){
            echo $this->twig->render('reserva.html.twig', [
                'errores' => $errores,
                'app' => ['request' => $request]
            ]);
        }
        else{
            $destinatario = $config->get('MAIL_PERSONAL'); 
            $mailService = new MailService;
            $mailService->enviarConfirmacionReserva($destinatario, $this->model->fields);
            header("Location: /reserva-exitosa");
        }
    }
}