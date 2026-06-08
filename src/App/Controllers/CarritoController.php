<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;
use Paw\App\Models\LibroCollection;
use Paw\App\Models\Carrito;
use Paw\Core\Request;

class CarritoController extends Controller
{
    public ?string $modelName = LibroCollection::class;

    public function agregar()
    {
        $request = $this->request;
        $libro_id = $request->post()['libro_id'] ?? null;

        $userSession = Request::session('user');
        if (!$userSession) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'unauthorized']);
            exit;
        }

        if ($libro_id) {
            $libro = $this->model->get($libro_id);
            if ($libro) {
                $carrito = new Carrito();
                $carrito->agregarItem(
                    $libro->fields['id'],
                    $libro->fields['titulo'],
                    $libro->fields['precio'],
                    $libro->fields['imagen'] ?? 'default.png',
                    1
                );
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }

    public function vaciar()
    {
        $carrito = new Carrito();
        $carrito->vaciar();
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/catalogo';
        header("Location: $referer");
        exit;
    }

    public function actualizar()
    {
        $request = $this->request;
        $libro_id = $request->post()['libro_id'] ?? null;
        $cantidad = (int)($request->post()['cantidad'] ?? 1);

        if ($libro_id) {
            $carrito = new Carrito();
            $carrito->actualizarCantidad($libro_id, $cantidad);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/catalogo';
        $separator = strpos($referer, '?') !== false ? '&' : '?';
        header("Location: {$referer}{$separator}abrir_carrito=true");
        exit;
    }
}
