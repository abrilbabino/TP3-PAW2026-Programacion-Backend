<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Exception;

class Reserva extends Model{
    
    public $fields = [
        "nombre" => null,
        "email" => null,
        "telefono" => null,
        "cantidad" => null,
        "libro" => null,
    ];

    public function set(array $datos){
        $this->fields["nombre"] = $datos["nombre"] ?? null;
        $this->fields["email"] = $datos["email"] ?? null;
        $this->fields["telefono"] = $datos["tel"] ?? null;
        $this->fields["cantidad"] = $datos["cantidad"] ?? null;
        $this->fields["libro"] = $datos["libro"] ?? null;
    }

    public function validar() {
        $errores = [];

        if (empty($this->fields["nombre"])) {
            $errores['nombre'] = "El nombre es obligatorio.";
        }

        if (!filter_var($this->fields["email"], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El formato de email no es válido.";
        }

        if (empty($this->fields["libro"])) {
            $errores['libro'] = "Debe seleccionar un libro.";
        }
        else {
            $libroExistente = $this->queryBuilder->select("libro", [
                "titulo" => $this->fields["libro"]
            ]);

            if (!$libroExistente) {
                $errores['libro'] = "El libro seleccionado no existe en nuestro catálogo.";
            }
        }

        if (!is_numeric($this->fields["cantidad"]) || $this->fields["cantidad"] < 1) {
            $errores['cantidad'] = "La cantidad debe ser un número positivo mayor a cero.";
        }
        
        return $errores;
    }
}