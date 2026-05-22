<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;
use Paw\App\Models\Usuario;

class AuthController extends Controller
{
    public ?string $modelName = Usuario::class;

    public function register()
    {
        $request = $this->request;

        $name     = trim($request->get('name') ?? '');
        $email    = trim($request->get('email') ?? '');
        $username = trim($request->get('username') ?? '');
        $password = $request->get('password') ?? '';

        if (!$name || !$email || !$username || !$password) {
            $_SESSION['error_registro'] = 'Completá todos los campos.';
            header('Location: /');
            exit;
        }

        $existente = $this->model->findByUsername($username);
        if ($existente) {
            $_SESSION['error_registro'] = 'El nombre de usuario ya está en uso.';
            header('Location: /');
            exit;
        }

        $existeEmail = $this->model->findByEmail($email);
        if ($existeEmail) {
            $_SESSION['error_registro'] = 'El correo electrónico ya está registrado.';
            header('Location: /');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->model->crearUsuario([
            'nombre_completo' => $name,
            'email'          => $email,
            'usuario'        => $username,
            'contrasenia'     => $passwordHash,
        ]);

        $_SESSION['user'] = [
            'id'             => $userId,
            'nombre_usuario' => $username,
            'email'          => $email,
            'nombre_completo' => $name,
        ];

        header('Location: /');
        exit;
    }

    public function login()
    {
        $request = $this->request;

        $username = trim($request->get('nombre_usuario') ?? '');
        $password = $request->get('contrasena') ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error_login'] = 'Completá todos los campos.';
            header('Location: /');
            exit;
        }

        $usuario = $this->model->findByUsername($username);

        if (!$usuario || !password_verify($password, $usuario['contrasenia'])) {
            $_SESSION['error_login'] = 'Usuario o contraseña incorrectos.';
            header('Location: /');
            exit;
        }

        $_SESSION['user'] = [
            'id'              => $usuario['id'],
            'nombre_usuario'  => $usuario['usuario'],
            'email'           => $usuario['email'],
            'nombre_completo' => $usuario['nombre_completo'],
        ];

        header('Location: /');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /');
        exit;
    }
}
