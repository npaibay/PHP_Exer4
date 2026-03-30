<?php

namespace App\Helpers;

require_once __DIR__ . '/../Core/SessionManager.php';

use App\Core\SessionManager;

class FlashMessage
{
    public static function set(string $key, string $message): void
    {
        SessionManager::start();
        SessionManager::set($key, $message);
    }

    public static function get(string $key): ?string
    {
        SessionManager::start();

        if (!SessionManager::has($key)) {
            return null;
        }

        $message = SessionManager::get($key);
        SessionManager::remove($key);

        return $message;
    }
}