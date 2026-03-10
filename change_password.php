<?php
include 'db.php';
include 'auth.php';
require_login();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST['current_password'] ?? "";
    $new = $_POST['new_password'] ?? "";
    $confirm = $_POST['confirm_new_password'] ?? "";

    if ($current === "" || $new === "" || $confirm === "") {
        $error = "Please fill in all fields.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $uid = (int)$_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row || !password_verify($current, $row['password'])) {
            $error = "Current password is incorrect.";
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);

            $up = $conn->prepare("
                UPDATE users
                SET password=?, updated_on=NOW(), updated_by=?
                WHERE id=?
            ");
            $up->bind_param("sii", $hash, $uid, $uid);
            $up->execute();

            $_SESSION['flash_success'] = "Password updated successfully.";
            header("Location: home.php");
            exit;
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

    <?php if ($error !== ""): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
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
