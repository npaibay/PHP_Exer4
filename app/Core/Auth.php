<?php

namespace App\Core;

require_once __DIR__ . '/SessionManager.php';
require_once __DIR__ . '/../Models/User.php';

use App\Models\User;

class Auth
{
    public static function requireLogin(): void
    {
        SessionManager::start();

        if (!SessionManager::has('user_id')) {
            header('Location: index.php?controller=auth&action=showLogin');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        if (SessionManager::get('account_type') !== 'admin') {
            SessionManager::set('flash_error', 'Access denied.');
            header('Location: index.php?controller=home&action=index');
            exit;
        }
    }

    public static function requireAdminOrStaff(): void
    {
        self::requireLogin();

        $role = SessionManager::get('account_type', '');

        if (!in_array($role, ['admin', 'staff'], true)) {
            SessionManager::set('flash_error', 'Access denied.');
            header('Location: index.php?controller=home&action=index');
            exit;
        }
    }

    public static function check(): bool
    {
        SessionManager::start();
        return SessionManager::has('user_id');
    }

    public static function id(): ?int
    {
        SessionManager::start();
        return SessionManager::get('user_id');
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
        header('Location: index.php?controller=auth&action=showLogin');
        exit;
    }
}