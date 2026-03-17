<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Helpers/Validator.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Helpers\Validator;

SessionManager::start();

$error = '';
$usernameValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameValue = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!Validator::required($usernameValue) || !Validator::required($password))
    {
        $error = 'Please enter username and password.';
    } 
    elseif (Auth::login($usernameValue, $password)) 
    {
        header('Location: home.php');
        exit;
    }
    else 
    {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #ffffff;
            padding: 30px 25px;
            width: 340px;
            border: 1px solid #cccccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            background-color: #0066cc;
            color: white;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #004999;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .hint {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Login Portal</h1>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($usernameValue) ?>">

        <label>Password</label>
        <input type="password" name="password">

        <input type="submit" value="Login">
    </form>
    
    <div class="hint">Default admin: admin / admin12345</div>
</div>

</body>
</html>