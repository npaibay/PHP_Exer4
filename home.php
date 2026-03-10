<?php
include 'db.php';
include 'auth.php';

require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <title>School Encoding Module</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            height: 100vh;
            margin: 0;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 30px 35px;
            border: 1px solid #cccccc;
            width: 320px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .welcome {
            margin-bottom: 15px;
            font-size: 14px;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            display: block;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            background-color: #eeeeee;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>School Encoding Module</h1>

    <div class="welcome">
        Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
        (<?php echo htmlspecialchars($_SESSION['account_type']); ?>)
    </div>

    <?php flash_message(); ?>

    <ul>
        <li><a href="program_list.php">Programs</a></li>
        <li><a href="subject_list.php">Subjects</a></li>

        <?php if ($_SESSION['account_type'] === 'admin'): ?>
            <li><a href="users_list.php">User Accounts</a></li>
        <?php endif; ?>

        <li><a href="change_password.php">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

</body>
</html>
