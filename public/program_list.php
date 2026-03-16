<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/Program.php';
require_once __DIR__ . '/../app/Helpers/FlashMessage.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Models\Program;
use App\Helpers\FlashMessage;

SessionManager::start();
Auth::requireLogin();

$programModel = new Program();

$searchText = isset($_GET['search']) ? trim($_GET['search']) : '';

$currentUser = Auth::currentUser();
$role = $currentUser['account_type'] ?? '';
$canManage = in_array($role, ['admin', 'staff'], true);

$result = $programModel->search($searchText);
?>
<!DOCTYPE html>
<html>
<head>
<title>Programs List</title>
<style>
body { font-family: Arial, sans-serif; background-color:#f9f9f9; margin:30px; }
h1 { margin-bottom:10px; }
a { text-decoration:none; color:#06c; margin-right:10px; }
a:hover { text-decoration:underline; }
form { margin-top:15px; }
input[type="text"] { padding:6px; width:220px; }
input[type="submit"] { padding:6px 12px; cursor:pointer; }
table { border-collapse:collapse; width:100%; background:#fff; margin-top:15px; }
th,td { border:1px solid #ccc; padding:8px; text-align:left; }
th { background:#eee; }
</style>
</head>
<body>

<h1>Programs</h1>

<a href="home.php">Back to Home</a>

<?php if ($canManage): ?>
| <a href="program_new.php">Add New Program</a>
<?php endif; ?>

<?php FlashMessage::display(); ?>

<form method="get">
<input type="text" name="search" placeholder="Search by program code"
value="<?= htmlspecialchars($searchText) ?>">
<input type="submit" value="Search">
<a href="program_list.php">Reset</a>
</form>

<table>
<tr>
<th>Code</th>
<th>Title</th>
<th>Years</th>
<?php if ($canManage): ?>
<th>Action</th>
<?php endif; ?>
</tr>

<?php if ($result->num_rows === 0): ?>
<tr>
<td colspan="<?= $canManage ? 4 : 3 ?>" style="text-align:center;">
No programs found.
</td>
</tr>
<?php else: ?>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['code']) ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['years']) ?></td>

<?php if ($canManage): ?>
<td>
<a href="program_edit.php?program_id=<?= (int)$row['program_id'] ?>">Edit</a>
</td>
<?php endif; ?>

</tr>
<?php endwhile; ?>

<?php endif; ?>

</table>

</body>
</html>