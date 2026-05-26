<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;
use Paw\App\Models\Usuario;
use Paw\Core\Request;

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

        header('Content-Type: application/json');

        if (!$name || !$email || !$username || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Completá todos los campos.']);
            exit;
        }

        $existente = $this->model->findByUsername($username);
        if ($existente) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'El nombre de usuario ya está en uso.',
                'errors' => ['username' => 'El nombre de usuario ya está en uso.']
            ]);
            exit;
        }

        $existeEmail = $this->model->findByEmail($email);
        if ($existeEmail) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'El correo electrónico ya está registrado.',
                'errors' => ['email' => 'El correo electrónico ya está registrado.']
            ]);
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->model->crearUsuario([
            'nombre_completo' => $name,
            'email'          => $email,
            'usuario'        => $username,
            'contrasenia'     => $passwordHash,
        ]);

        Request::setSession('user', [
            'id'             => $userId,
            'nombre_usuario' => $username,
            'email'          => $email,
            'nombre_completo' => $name,
        ]);

        echo json_encode(['status' => 'success']);
        exit;
    }

    public function login()
    {
        $request = $this->request;

        $username = trim($request->get('nombre_usuario') ?? '');
        $password = $request->get('contrasena') ?? '';

        header('Content-Type: application/json');

        if (empty($username) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Completá todos los campos.']);
            exit;
        }

        $usuarioLogueado = $this->model->autenticar($username, $password);

        if (!$usuarioLogueado) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Usuario o contraseña incorrectos.',
                'errors' => ['contrasena' => 'Usuario o contraseña incorrectos.']
            ]);
            exit;
        }

        Request::setSession('user', $usuarioLogueado);

        echo json_encode(['status' => 'success']);
        exit;
    }

    public function logout()
    {
        Request::destroySession();
        header('Location: /');
        exit;
    }
}
