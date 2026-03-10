<?php
include 'db.php';
include 'auth.php';
require_admin();

require_once 'models/usersModel.php';
$usersModel = new Users($conn);

$error = "";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$user = $usersModel->getById($id);
if (!$user) {
    $_SESSION['flash'] = "User not found.";
    header("Location: users_list.php");
    exit;
}

$username_val = $user['username'];
$account_type_val = $user['account_type'];

$valid_roles = ['admin', 'staff', 'teacher', 'student'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_val = trim($_POST['username'] ?? "");
    $account_type_val = $_POST['account_type'] ?? "";

    if ($username_val === "") {
        $error = "Username is required.";
    } elseif (!in_array($account_type_val, $valid_roles)) {
        $error = "Invalid account type.";
    } elseif ($usersModel->usernameExists($username_val, $id)) {
        $error = "Username already exists.";
    } else {
        $updated_by = (int)($_SESSION['user_id'] ?? 0);

        if ($usersModel->update($id, $username_val, $account_type_val, $updated_by)) {
            $_SESSION['flash_success'] = "User updated successfully.";
            header("Location: users_list.php");
            exit;
        } else {
            $error = "Failed to update user.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin:0; height:100vh; display:flex; justify-content:center; align-items:center; }
        .card { background:#fff; width:360px; padding:30px 25px; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,.05); }
        h1 { text-align:center; margin-bottom:15px; }
        a { display:block; text-align:center; margin-bottom:15px; text-decoration:none; color:#06c; }
        label { font-weight:bold; }
        input[type="text"], select { width:100%; padding:8px; margin-top:5px; margin-bottom:12px; box-sizing:border-box; }
        input[type="submit"] { width:100%; padding:10px; cursor:pointer; font-weight:bold; background:#06c; color:#fff; border:none; }
        input[type="submit"]:hover { background:#049; }
        .error { color:red; text-align:center; margin-bottom:12px; }
    </style>
</head>
<body>

<div class="card">
    <h1>Edit User</h1>
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

        <input type="submit" value="Update User">
    </form>
</div>

</body>
</html>