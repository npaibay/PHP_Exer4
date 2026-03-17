<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Helpers/Hash.php';
require_once __DIR__ . '/../app/Helpers/Validator.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Helpers\Validator;
use App\Models\User;
use App\Helpers\Hash;

SessionManager::start();
Auth::requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

    if (!Validator::required($currentPassword) || 
        !Validator::required($newPassword) || 
        !Validator::required($confirmNewPassword)) 
    {
        $error = 'Please fill in all fields.';
    } 
    elseif (!Validator::minLength($newPassword, 8)) 
    {
        $error = 'New password must be at least 8 characters.';
    } 
    elseif ($newPassword !== $confirmNewPassword) 
    {
        $error = 'New passwords do not match.';
    } 
    else 
    {
        $userId = (int) SessionManager::get('user_id');

        $userModel = new User();
        $user = $userModel->getById($userId);

        if (!$user) {
            $error = 'User account not found.';
        } else {
            $userWithPassword = $userModel->getByUsername($user['username']);

            if (!$userWithPassword || !Hash::verify($currentPassword, $userWithPassword['password'])) {
                $error = 'Current password is incorrect.';
            } else {
                $newPasswordHash = Hash::make($newPassword);

                if ($userModel->updatePassword($userId, $newPasswordHash, $userId)) {
                    SessionManager::set('flash_success', 'Password updated successfully.');
                    header('Location: home.php');
                    exit;
                } else {
                    $error = 'Failed to update password.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
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
            width: 360px;
            padding: 30px 25px;
            border: 1px solid #cccccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        h1 {
            text-align: center;
            margin-bottom: 15px;
        }

        a {
            display: block;
            text-align: center;
            margin-bottom: 15px;
            text-decoration: none;
            color: #0066cc;
        }

        label {
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            background-color: #0066cc;
            color: #ffffff;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #004999;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Change Password</h1>
    <a href="home.php">Back to Home</a>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Current Password</label>
        <input type="password" name="current_password">

        <label>New Password</label>
        <input type="password" name="new_password">

        <label>Confirm New Password</label>
        <input type="password" name="confirm_new_password">

        <input type="submit" value="Update Password">
    </form>
</div>

</body>
</html>