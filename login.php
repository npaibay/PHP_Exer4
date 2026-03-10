<?php
include 'db.php';
session_start();

$error = "";
$username_val = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username_val = trim($_POST['username']);
    $password = $_POST['password'] ?? "";

    if($username_val == "" || $password == "")
    {
        $error = "Please enter username and password.";
    }
    else
    {
        $stmt = $conn->prepare("SELECT id, username, password, account_type FROM users WHERE username = ?");
        $stmt->bind_param("s", $username_val);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if(!$user || !password_verify($password, $user['password']))
        {
            $error = "Invalid username or password.";
        }
        else
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['account_type'] = $user['account_type'];

            header("Location: home.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
            padding: 30px 25px;
            width: 340px;
            border: 1px solid #cccccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            background-color: #0066cc;
            color: white;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #004999;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .hint {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Login</h1>

    <?php if($error != "") echo "<div class='error'>".htmlspecialchars($error)."</div>"; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username_val); ?>">

        <label>Password</label>
        <input type="password" name="password">

        <input type="submit" value="Login">
    </form>

    <div class="hint">Default admin: admin / admin123</div>
</div>

</body>
</html>
