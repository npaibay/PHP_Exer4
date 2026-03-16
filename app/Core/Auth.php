<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function requireLogin(): void
    {
        SessionManager::start();

        if (!SessionManager::has('user_id')) {
            header('Location: login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        if (SessionManager::get('account_type') !== 'admin') {
            SessionManager::set('flash_error', 'Access denied.');
            header('Location: home.php');
            exit;
        }
    }

    public static function requireAdminOrStaff(): void
    {
        self::requireLogin();

        $role = SessionManager::get('account_type', '');

        if (!in_array($role, ['admin', 'staff'], true)) {
            SessionManager::set('flash_error', 'Access denied.');
            header('Location: home.php');
            exit;
        }
    }

    public static function currentUser(): ?array
    {
        SessionManager::start();

        if (!SessionManager::has('user_id')) {
            return null;
        }

        return [
            'user_id' => SessionManager::get('user_id'),
            'username' => SessionManager::get('username'),
            'account_type' => SessionManager::get('account_type', ''),
        ];
    }

    public static function login(string $username, string $password): bool
    {
        SessionManager::start();

        $userModel = new User();
        $user = $userModel->getByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        SessionManager::set('user_id', $user['id']);
        SessionManager::set('username', $user['username']);
        SessionManager::set('account_type', $user['account_type']);

        return true;
    }

    public static function logout(): void
    {
        SessionManager::start();
        SessionManager::destroy();
        header('Location: login.php');
        exit;
    }
}