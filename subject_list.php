<?php
include 'db.php';
include 'auth.php';
require_login();

require_once 'models/SubjectModel.php';
$subjectModel = new Subject($conn);

$search_text = isset($_GET['search']) ? trim($_GET['search']) : "";
$role = $_SESSION['account_type'] ?? '';
$can_manage = in_array($role, ['admin', 'staff']);

$result = $subjectModel->search($search_text);
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
        <?php if ($can_manage): ?>
            | <a href="subject_new.php">Add New Subject</a>
        <?php endif; ?>
    </div>

    <form method="get">
        <input type="text" name="search" placeholder="Search by code"
               value="<?php echo htmlspecialchars($search_text); ?>">
        <input type="submit" value="Search">
        <a href="subject_list.php">Reset</a>
    </form>

    <table>
        <tr>
            <th>Code</th>
            <th>Title</th>
            <th>Unit</th>
            <?php if ($can_manage): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>

        <?php if ($result->num_rows == 0): ?>
            <tr>
                <td colspan="<?php echo $can_manage ? 4 : 3; ?>" class="no-data">
                    No subjects found.
                </td>
            </tr>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit']); ?></td>
                    <?php if ($can_manage): ?>
                        <td>
                            <a href="subject_edit.php?subject_id=<?php echo (int)$row['subject_id']; ?>">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</div>

</body>
</html>