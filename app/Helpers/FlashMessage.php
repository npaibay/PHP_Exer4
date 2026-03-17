<?php

namespace App\Helpers;

require_once __DIR__ . '/../Core/SessionManager.php';

use App\Core\SessionManager;

class FlashMessage
{
    public static function display(): void
    {
        SessionManager::start();

        if (SessionManager::get('flash_success')) {
            echo "<div style='color: green; text-align: center; margin-bottom: 10px; font-weight: bold;'>"
                . htmlspecialchars(SessionManager::get('flash_success'))
                . "</div>";
            SessionManager::remove('flash_success');
        }

        if (SessionManager::get('flash_error')) {
            echo "<div style='color: red; text-align: center; margin-bottom: 10px; font-weight: bold;'>"
                . htmlspecialchars(SessionManager::get('flash_error'))
                . "</div>";
            SessionManager::remove('flash_error');
        }
    }
}