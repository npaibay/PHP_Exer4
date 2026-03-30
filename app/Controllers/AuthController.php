<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('index.php?controller=home&action=index');
        }

        $this->view('auth/login');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=auth&action=showLogin');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::login($username, $password)) {
            $this->redirect('index.php?controller=home&action=index');
        }

        $error = 'Invalid username or password.';
        $this->view('auth/login', compact('error'));
    }

    public function logout(): void
    {
        Auth::logout();
    }
}