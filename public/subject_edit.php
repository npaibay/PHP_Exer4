<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/Subject.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Models\Subject;

SessionManager::start();
Auth::requireAdminOrStaff();

$subjectModel = new Subject();

$error = '';
$id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

$subject = $subjectModel->getById($id);

if (!$subject) {
    SessionManager::set('flash_error', 'Subject not found.');
    header('Location: subject_list.php');
    exit;
}

$codeValue = $subject['code'];
$titleValue = $subject['title'];
$unitValue = $subject['unit'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeValue = trim($_POST['code'] ?? '');
    $titleValue = trim($_POST['title'] ?? '');
    $unitValue = trim($_POST['unit'] ?? '');

    $unitInt = (int) $unitValue;

    if ($codeValue === '' || $titleValue === '' || !is_numeric($unitValue) || $unitInt <= 0) {
        $error = 'Please fill all fields correctly. Unit must be a number greater than 0.';
    } elseif ($subjectModel->codeExists($codeValue, $id)) {
        $error = "Subject code '{$codeValue}' already exists!";
    } else {
        $updatedBy = (int) SessionManager::get('user_id');

        if ($subjectModel->update($id, $codeValue, $titleValue, $unitInt, $updatedBy)) {
            SessionManager::set('flash_success', 'Subject updated successfully.');
            header('Location: subject_list.php');
            exit;
        } else {
            $error = 'Failed to update subject.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Subject</title>
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
    <h1>Edit Subject</h1>

    <div class="back">
        <a href="subject_list.php">Back to Subjects</a>
    </div>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Code</label>
        <input type="text" name="code" value="<?= htmlspecialchars($codeValue) ?>" required>

        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($titleValue) ?>" required>

        <label>Unit</label>
        <input type="number" name="unit" value="<?= htmlspecialchars($unitValue) ?>" required>

        <input type="submit" value="Update">
    </form>
</div>

</body>
</html>