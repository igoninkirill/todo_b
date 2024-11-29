<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);

            $model = new User();
            $user = $model->findByUsername($username);

            if ($user && password_verify($password, $user['password']) && $user['role'] === 'admin') {
                $_SESSION['is_admin'] = true;
                $_SESSION['user_id'] = $user['id'];
                header('Location: /tasks');
                exit;
            }

            echo $this->twig->render('auth/login.html.twig', [
                'error' => 'Invalid username or password',
            ]);
        } else {
            echo $this->twig->render('auth/login.html.twig');
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /tasks');
        exit;
    }
}
