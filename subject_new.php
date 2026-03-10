<?php
include 'db.php';
include 'auth.php';
require_admin_or_staff();

require_once 'models/SubjectModel.php';
$subjectModel = new Subject($conn);

$error = "";
$code_val = "";
$title_val = "";
$unit_val = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code_val  = trim($_POST['code'] ?? "");
    $title_val = trim($_POST['title'] ?? "");
    $unit_val  = trim($_POST['unit'] ?? "");

    $unit_int = (int)$unit_val;

    if ($code_val === "" || $title_val === "" || !is_numeric($unit_val) || $unit_int <= 0) {
        $error = "Please fill all fields correctly. Unit must be a number greater than 0.";
    } elseif ($subjectModel->codeExists($code_val)) {
        $error = "Subject code '" . htmlspecialchars($code_val) . "' already exists!";
    } else {
        $created_by = (int)($_SESSION['user_id'] ?? 0);

        if ($subjectModel->create($code_val, $title_val, $unit_int, $created_by)) {
            $_SESSION['flash_success'] = "Subject added successfully.";
            header("Location: subject_list.php");
            exit;
        } else {
            $error = "Failed to add subject.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Subject</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { background-color: #ffffff; width: 350px; padding: 25px 30px; border: 1px solid #cccccc; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); }
        h1 { text-align: center; margin-bottom: 15px; }
        .back { text-align: center; margin-bottom: 15px; }
        .back a { text-decoration: none; color: #0066cc; }
        label { font-weight: bold; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 12px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; padding: 10px; cursor: pointer; font-weight: bold; background-color: #0066cc; color: white; border: none; }
        input[type="submit"]:hover { background-color: #004999; }
        .error { color: red; text-align: center; margin-bottom: 12px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Add New Subject</h1>

    <div class="back">
        <a href="subject_list.php">Back to Subjects</a>
    </div>

    <?php if ($error !== ""): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Code</label>
        <input type="text" name="code" value="<?php echo htmlspecialchars($code_val); ?>" required>

        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title_val); ?>" required>

        <label>Unit</label>
        <input type="number" name="unit" value="<?php echo htmlspecialchars($unit_val); ?>" required>

        <input type="submit" value="Save">
    </form>
</div>

</body>
</html>