<?php

namespace Paw\App\Models;

class Carrito
{
    public function __construct()
    {
        // Inicializar carrito en la sesión si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    /**
     * Agrega un ítem al carrito o incrementa su cantidad si ya existe.
     */
    public function agregarItem($id, $titulo, $precio, $imagen, $cantidad = 1)
    {
        $encontrado = false;
        
        // Si el libro ya está, aumentar cantidad
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }

        // Si no está, agregarlo al array
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $id,
                'titulo' => $titulo,
                'precio' => floatval($precio),
                'imagen' => $imagen,
                'cantidad' => $cantidad
            ];
        }
    }

    /**
     * Actualiza la cantidad de un ítem en el carrito.
     * Si la cantidad es <= 0, elimina el ítem.
     */
    public function actualizarCantidad($id, $cantidad)
    {
        foreach ($_SESSION['carrito'] as $key => &$item) {
            if ($item['id'] == $id) {
                if ($cantidad <= 0) {
                    unset($_SESSION['carrito'][$key]);
                    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                } else {
                    $item['cantidad'] = $cantidad;
                }
                break;
            }
        }
    }

    /**
     * Vacía el carrito por completo.
     */
    public function vaciar()
    {
        $_SESSION['carrito'] = [];
    }

    /**
     * Retorna los ítems del carrito.
     */
    public function getItems()
    {
        return $_SESSION['carrito'];
    }
}
