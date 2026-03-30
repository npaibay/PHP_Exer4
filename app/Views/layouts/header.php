<?php

use App\Core\Auth;
use App\Helpers\FlashMessage;

$flashSuccess = FlashMessage::get('flash_success');
$flashError = FlashMessage::get('flash_error');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>School Encoding Module</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #0b1f3a;
            color: #1f2a1f;
        }

        /* Login Center */
        .page-center {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card (Login) */
        .card {
            background: #ffffff;
            width: 380px;
            padding: 32px 28px;
            border-radius: 0;
            border-top: 6px solid #003a8f
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* Main Container */
        .container {
            max-width: 1050px;
            margin: 40px auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 0;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* Headers */
        h1 {
            margin-top: 0;
            font-size: 34px;
            color: #003a8f;
            border-bottom: 3px solid #d4af37;
            padding-bottom: 10px;
            text-align: center;
        }

        /* Labels */
        label {
            font-weight: bold;
            margin-bottom: 6px;
            display: block;
        }

        /* Inputs */
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccd5cc;
            border-radius: 0;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #1b5e20;
            box-shadow: 0 0 0 2px rgba(27, 94, 32, 0.15);
        }

        /* Buttons */
        button, .btn, input[type="submit"] {
            background: #003a8f;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 0;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover, .btn:hover, input[type="submit"]:hover {
            background: #002a66;
        }

        /* Links */
        a {
            color: #003a8f;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Actions */
        .actions {
            margin-bottom: 16px;
        }

        /* Search */
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .search-form input {
            flex: 1;
        }

        .reset-link {
            background: #eef3ee;
            padding: 10px 12px;
            border-radius: 0;
        }

        .reset-link:hover {
            background: #dde7dd;
            text-decoration: none;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            border-radius: 0;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e6eee6;
        }

        th {
            background: #003a8f;
            color: white;
        }

        tr:nth-child(even) {
            background: #f7faf7;
        }

        tr:hover {
            background: #edf5ed;
        }

        /* Messages */
        .error {
            background: #fdecea;
            color: #b71c1c;
            padding: 12px;
            border-left: 5px solid #c62828;
            border-radius: 0;
            margin-bottom: 15px;
        }

        .success {
            background: #e8f5e9;
            color: #1b5e20;
            padding: 12px;
            border-left: 5px solid #2e7d32;
            border-radius: 0;
            margin-bottom: 15px;
        }

        /* Dashboard Links */
        .dashboard-links {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .dashboard-links li {
            margin-bottom: 10px;
        }

        .dashboard-links a {
            display: block;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #dfe8df;
            background: #fafdf9;
        }

        .dashboard-links a:hover {
            background: #eef5ee;
        }

        .blur-text {
            filter: blur(4px);
            transition: filter 0.3s ease;
            display: inline-block;
        }

        .blur-text:hover {
            filter: blur(0);
        }

        .hint {
            text-align: center;
            font-size: 13px;
            margin-top: 12px;
            color: #555;
        }
    </style>
</head>
<body>

<?php if (!Auth::check()): ?>
    <div class="page-center">
<?php else: ?>
    <div class="container">
<?php endif; ?>

<?php if ($flashSuccess): ?>
    <div class="success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>

<?php if ($flashError): ?>
    <div class="error"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>