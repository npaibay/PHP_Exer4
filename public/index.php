<?php

require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/Controller.php';

require_once __DIR__ . '/../app/Helpers/Hash.php';
require_once __DIR__ . '/../app/Helpers/Validator.php';
require_once __DIR__ . '/../app/Helpers/FlashMessage.php';

require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Program.php';
require_once __DIR__ . '/../app/Models/Subject.php';

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/ProgramController.php';
require_once __DIR__ . '/../app/Controllers/SubjectController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

use App\Core\SessionManager;

SessionManager::start();

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'showLogin';

$routes = [
    'auth' => \App\Controllers\AuthController::class,
    'home' => \App\Controllers\HomeController::class,
    'program' => \App\Controllers\ProgramController::class,
    'subject' => \App\Controllers\SubjectController::class,
    'user' => \App\Controllers\UserController::class,
];

if (!isset($routes[$controller])) {
    die('Controller not found.');
}

$controllerClass = $routes[$controller];
$controllerObject = new $controllerClass();

if (!method_exists($controllerObject, $action)) {
    die('Action not found.');
}

$controllerObject->$action();