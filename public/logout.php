<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';

use App\Core\SessionManager;
use App\Core\Auth;

SessionManager::start();
Auth::logout();