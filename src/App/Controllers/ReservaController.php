<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Reserva;
use Paw\Core\Controller;
use Paw\Core\MailService;

class ReservaController extends Controller{

    public ?string $modelName = Reserva::class;

    public function reserva()
    {
        if (!\Paw\Core\Request::session('user')) {
            header("Location: /?login=true");
            exit;
        }
        echo $this->twig->render('reserva.html.twig', [
            'app' => ['request' => $this->request]
        ]);
    }

    public function procesarReserva()
    {
        if (!\Paw\Core\Request::session('user')) {
            header("Location: /?login=true");
            exit;
        }

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
            // 1. Guardar en la base de datos
            $this->model->insert();

            // 2. Enviar email
            $destinatario = $config->get('MAIL_PERSONAL'); 
            $mailService = new MailService;
            $mailService->enviarConfirmacionReserva($destinatario, $this->model->fields);
            
            header("Location: /reserva-exitosa");
        }
    }

    public function pedidos()
    {
        // Seguridad: Solo el staff puede acceder
        $userSession = \Paw\Core\Request::session('user');
        if (!$userSession || !isset($userSession['rol']) || $userSession['rol'] !== 'staff') {
            header("Location: /");
            exit;
        }

        // Importamos dinámicamente la colección porque el Controller está atado al Model singular
        $reservaCollection = new \Paw\App\Models\ReservaCollection;
        // Le pasamos el mismo QueryBuilder que tiene el modelo actual
        $reservaCollection->setQueryBuilder($this->model->getQueryBuilder());

        $pedidos = $reservaCollection->getAll();

        echo $this->twig->render('pedidos.html.twig', [
            'pedidos' => $pedidos
        ]);
    }
}