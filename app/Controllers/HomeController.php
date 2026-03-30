<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $user = Auth::currentUser();
        $this->view('home/index', compact('user'));
    }
}