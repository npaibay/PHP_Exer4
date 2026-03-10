<?php
include 'db.php';
include 'auth.php';
require_admin();

require_once 'models/usersModel.php';
$usersModel = new Users($conn);

$result = $usersModel->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; margin:30px; }
        h1 { margin-bottom:10px; }
        a { text-decoration:none; color:#06c; margin-right:10px; }
        a:hover { text-decoration:underline; }
        table { border-collapse:collapse; width:100%; background:#fff; margin-top:15px; }
        th, td { border:1px solid #ccc; padding:8px; text-align:left; }
        th { background:#eee; }
    </style>
</head>
<body>

<h1>User Accounts</h1>

<a href="home.php">Back to Home</a> |
<a href="users_new.php">Add New User</a>

<?php flash_message(); ?>

<table>
    <tr>
        <th>Username</th>
        <th>Account Type</th>
        <th>Created On</th>
        <th>Updated On</th>
        <th>Action</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['account_type']); ?></td>
                <td><?php echo htmlspecialchars($row['created_on']); ?></td>
                <td><?php echo htmlspecialchars($row['updated_on'] ?? ""); ?></td>
                <td><a href="users_edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No users found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>