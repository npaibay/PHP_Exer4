<?php
include 'db.php';
include 'auth.php';
require_login();

require_once 'models/ProgramModel.php';
$programModel = new ProgramModel($conn);

$search_text = isset($_GET['search']) ? trim($_GET['search']) : "";
$role = $_SESSION['account_type'] ?? '';
$can_manage = in_array($role, ['admin', 'staff']);

$result = $programModel->search($search_text);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Programs List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 30px;
        }
        h1 { margin-bottom: 10px; }
        a {
            text-decoration: none;
            color: #0066cc;
            margin-right: 10px;
        }
        a:hover { text-decoration: underline; }
        form { margin-top: 15px; }
        input[type="text"] { padding: 6px; width: 220px; }
        input[type="submit"] { padding: 6px 12px; cursor: pointer; }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #ffffff;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }
        th { background-color: #eeeeee; }
    </style>
</head>
<body>

<h1>Programs</h1>

<a href="home.php">Back to Home</a>

<?php if ($can_manage): ?>
    | <a href="program_new.php">Add New Program</a>
<?php endif; ?>

<form method="get">
    <input type="text" name="search" placeholder="Search by program code"
           value="<?php echo htmlspecialchars($search_text); ?>">
    <input type="submit" value="Search">
    <a href="program_list.php">Reset</a>
</form>

<table>
    <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Years</th>
        <?php if ($can_manage): ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>

    <?php if ($result->num_rows == 0): ?>
        <tr>
            <td colspan="<?php echo $can_manage ? 4 : 3; ?>" style="text-align:center;">
                No programs found.
            </td>
        </tr>
    <?php else: ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $id    = (int)$row['program_id'];
                $code  = htmlspecialchars($row['code']);
                $title = htmlspecialchars($row['title']);
                $years = htmlspecialchars($row['years']);
            ?>
            <tr>
                <td><?php echo $code; ?></td>
                <td><?php echo $title; ?></td>
                <td><?php echo $years; ?></td>
                <?php if ($can_manage): ?>
                    <td><a href="program_edit.php?program_id=<?php echo $id; ?>">Edit</a></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

</body>
</html>