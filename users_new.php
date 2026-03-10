<?php
include 'db.php';
include 'auth.php';
require_admin();

require_once 'models/usersModel.php';
$usersModel = new Users($conn);

$error = "";
$username_val = "";
$account_type_val = "student";

$valid_roles = ['admin', 'staff', 'teacher', 'student'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_val = trim($_POST['username'] ?? "");
    $account_type_val = $_POST['account_type'] ?? "";
    $password = $_POST['password'] ?? "";
    $confirm  = $_POST['confirm_password'] ?? "";

    if ($username_val === "" || $password === "" || $confirm === "") {
        $error = "Please fill in all fields.";
    } elseif (!in_array($account_type_val, $valid_roles)) {
        $error = "Invalid account type.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($usersModel->usernameExists($username_val)) {
        $error = "Username already exists.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $created_by = (int)($_SESSION['user_id'] ?? 0);

        if ($usersModel->create($username_val, $hash, $account_type_val, $created_by)) {
            $_SESSION['flash_success'] = "User added successfully.";
            header("Location: users_list.php");
            exit;
        } else {
            $error = "Failed to create user.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; height: 100vh; display:flex; justify-content:center; align-items:center; }
        .card { background:#fff; width:360px; padding:30px 25px; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,.05); }
        h1 { text-align:center; margin-bottom:15px; }
        a { display:block; text-align:center; margin-bottom:15px; text-decoration:none; color:#06c; }
        label { font-weight:bold; }
        input[type="text"], input[type="password"], select { width:100%; padding:8px; margin-top:5px; margin-bottom:12px; box-sizing:border-box; }
        input[type="submit"] { width:100%; padding:10px; cursor:pointer; font-weight:bold; background:#06c; color:#fff; border:none; }
        input[type="submit"]:hover { background:#049; }
        .error { color:red; text-align:center; margin-bottom:12px; }
    </style>
</head>
<body>

<div class="card">
    <h1>Add New User</h1>
    <a href="users_list.php">Back to Users</a>

    <?php if ($error !== ""): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username_val); ?>">

        <label>Account Type</label>
        <select name="account_type">
            <?php foreach ($valid_roles as $r): ?>
                <option value="<?php echo htmlspecialchars($r); ?>" <?php echo ($account_type_val === $r) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($r); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Password</label>
        <input type="password" name="password">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password">

        <input type="submit" value="Create User">
    </form>
</div>

</body>
</html>