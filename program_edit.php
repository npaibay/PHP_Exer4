<?php
include 'db.php';
include 'auth.php';
require_admin_or_staff();

require_once 'models/ProgramModel.php';
$programModel = new ProgramModel($conn);

$error = "";
$id = isset($_GET['program_id']) ? (int)$_GET['program_id'] : 0;

$program = $programModel->getById($id);
if (!$program) {
    $_SESSION['flash'] = "Program not found.";
    header("Location: program_list.php");
    exit;
}

$code_val  = $program['code'];
$title_val = $program['title'];
$years_val = $program['years'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code_val  = trim($_POST['code'] ?? "");
    $title_val = trim($_POST['title'] ?? "");
    $years_val = trim($_POST['years'] ?? "");

    $years_int = (int)$years_val;

    if ($code_val === "" || $title_val === "" || !is_numeric($years_val) || $years_int < 1 || $years_int > 6) {
        $error = "Please fill all fields correctly. Years must be between 1 and 6.";
    } elseif ($programModel->codeExists($code_val, $id)) {
        $error = "Program code '" . htmlspecialchars($code_val) . "' already exists.";
    } else {
        $updated_by = (int)($_SESSION['user_id'] ?? 0);

        if ($programModel->update($id, $code_val, $title_val, $years_int, $updated_by)) {
            $_SESSION['flash_success'] = "Program updated successfully.";
            header("Location: program_list.php");
            exit;
        } else {
            $error = "Failed to update program.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Program</title>
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
            width: 340px;
            padding: 30px 25px;
            border: 1px solid #cccccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        h1 { text-align: center; margin-bottom: 20px; }
        a {
            display: block;
            text-align: center;
            margin-bottom: 15px;
            text-decoration: none;
            color: #0066cc;
        }
        label { font-weight: bold; }
        input[type="text"], input[type="number"] {
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
            color: #ffffff;
            border: none;
        }
        input[type="submit"]:hover { background-color: #004999; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <h1>Edit Program</h1>
    <a href="program_list.php">Back to Programs</a>

    <?php if ($error !== ""): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Code</label>
        <input type="text" name="code" value="<?php echo htmlspecialchars($code_val); ?>" required>

        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title_val); ?>" required>

        <label>Years</label>
        <input type="number" name="years" value="<?php echo htmlspecialchars($years_val); ?>" required>

        <input type="submit" value="Update">
    </form>
</div>

</body>
</html>