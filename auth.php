<?php
    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }

    function require_login()
    {
        if (!isset($_SESSION["user_id"]))
        {
            header("Location: login.php");
            exit;
        }
    }

    function require_admin()
    {
        require_login();
        if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] !== 'admin')
        {
            $_SESSION['flash_error'] = "Access denied.";
            header("Location: home.php");
            exit;
        }
    }

    function require_admin_or_staff()
    {
        require_login();
        $role = $_SESSION['account_type'] ?? '';
        if (!in_array($role, ['admin', 'staff']))
        {
            $_SESSION['flash'] = "Access denied.";
            header("Location: home.php");
            exit;
        }
    }

    function flash_message()
    {
        if (!empty($_SESSION['flash_success'])) {
            echo "<div style='color: green; text-align: center; margin-bottom: 10px; font-weight: bold;'>"
                . htmlspecialchars($_SESSION['flash_success']) .
                "</div>";
            unset($_SESSION['flash_success']);
        }

        if (!empty($_SESSION['flash_error'])) {
            echo "<div style='color: red; text-align: center; margin-bottom: 10px; font-weight: bold;'>"
                . htmlspecialchars($_SESSION['flash_error']) .
                "</div>";
            unset($_SESSION['flash_error']);
        }
    }

    function current_user()
    {
        if (!isset($_SESSION['user_id'])) return null;
        return
        [
            "user_id" => $_SESSION['user_id'],
            "username" => $_SESSION['username'],
            "account_type" => $_SESSION['account_type'] ?? ''
        ];
    }
?>

