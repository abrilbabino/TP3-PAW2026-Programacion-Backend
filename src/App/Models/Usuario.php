<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Usuario extends Model
{
    public $table = 'usuarios';

    public function crearUsuario(array $datos): int
    {
        return $this->queryBuilder->insert($this->table, $datos);
    }

    public function findByUsername(string $username): ?array
    {
        $resultados = $this->queryBuilder->select($this->table, ['usuario' => $username]);
        return $resultados ? $resultados[0] : null;
    }

    public function findByEmail(string $email): ?array
    {
        $resultados = $this->queryBuilder->select($this->table, ['email' => $email]);
        return $resultados ? $resultados[0] : null;
    }

    public function autenticar(string $username, string $password): ?array
    {
        $usuario = $this->findByUsername($username);

        if (!$usuario || !password_verify($password, $usuario['contrasenia'])) {
            return null;
        }

        // Devolvemos los datos seguros sin la contraseña
        return [
            'id'              => $usuario['id'],
            'nombre_usuario'  => $usuario['usuario'],
            'email'           => $usuario['email'],
            'nombre_completo' => $usuario['nombre_completo'],
            'rol'             => $usuario['rol'] ?? 'cliente',
        ];
    }
}
