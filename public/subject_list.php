<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/Subject.php';
require_once __DIR__ . '/../app/Helpers/FlashMessage.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Models\Subject;
use App\Helpers\FlashMessage;

SessionManager::start();
Auth::requireLogin();

$subjectModel = new Subject();

$searchText = isset($_GET['search']) ? trim($_GET['search']) : '';
$currentUser = Auth::currentUser();
$role = $currentUser['account_type'] ?? '';
$canManage = in_array($role, ['admin', 'staff'], true);

$result = $subjectModel->search($searchText);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subjects List</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; }
        .container { padding: 30px; }
        h1 { margin-bottom: 10px; }
        .nav { margin-bottom: 15px; }
        .nav a { text-decoration: none; color: #0066cc; margin-right: 10px; }
        form { margin-bottom: 15px; }
        input[type="text"] { padding: 5px; width: 220px; }
        input[type="submit"] { padding: 5px 10px; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; background-color: #ffffff; }
        th, td { border: 1px solid #cccccc; padding: 8px; text-align: left; }
        th { background-color: #eeeeee; }
        tr:hover { background-color: #f2f2f2; }
        .no-data { text-align: center; color: #666666; }
    </style>
</head>
<body>

<div class="container">
    <h1>Subjects</h1>

    <div class="nav">
        <a href="home.php">Back to Home</a>
        <?php if ($canManage): ?>
            | <a href="subject_new.php">Add New Subject</a>
        <?php endif; ?>
    </div>

    <?php FlashMessage::display(); ?>

    <form method="get">
        <input type="text" name="search" placeholder="Search by code"
               value="<?= htmlspecialchars($searchText) ?>">
        <input type="submit" value="Search">
        <a href="subject_list.php">Reset</a>
    </form>

    <table>
        <tr>
            <th>Code</th>
            <th>Title</th>
            <th>Unit</th>
            <?php if ($canManage): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>

        <?php if ($result->num_rows === 0): ?>
            <tr>
                <td colspan="<?= $canManage ? 4 : 3 ?>" class="no-data">
                    No subjects found.
                </td>
            </tr>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['code']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['unit']) ?></td>
                    <?php if ($canManage): ?>
                        <td>
                            <a href="subject_edit.php?subject_id=<?= (int) $row['subject_id'] ?>">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</div>

</body>
</html>