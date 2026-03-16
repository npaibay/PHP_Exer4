<?php

require_once __DIR__ . '/../app/Core/SessionManager.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Models/Program.php';

use App\Core\SessionManager;
use App\Core\Auth;
use App\Models\Program;

SessionManager::start();
Auth::requireAdminOrStaff();

$programModel = new Program();

$error = '';
$codeValue = '';
$titleValue = '';
$yearsValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$codeValue  = trim($_POST['code'] ?? '');
$titleValue = trim($_POST['title'] ?? '');
$yearsValue = trim($_POST['years'] ?? '');

$yearsInt = (int)$yearsValue;

if ($codeValue === '' || $titleValue === '' || !is_numeric($yearsValue) || $yearsInt < 1 || $yearsInt > 6) {
$error = 'Please fill all fields correctly. Years must be between 1 and 6.';
}

elseif ($programModel->codeExists($codeValue)) {
$error = "Program code '{$codeValue}' already exists.";
}

else {

$createdBy = (int) SessionManager::get('user_id');

if ($programModel->create($codeValue,$titleValue,$yearsInt,$createdBy)) {

SessionManager::set('flash_success','Program added successfully.');

header('Location: program_list.php');
exit;

}

$error = 'Failed to add program.';
}

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add New Program</title>
<style>
body { font-family: Arial, sans-serif; background:#f9f9f9; margin:0; height:100vh; display:flex; justify-content:center; align-items:center; }
.card { background:#fff; width:340px; padding:30px 25px; border:1px solid #ccc; box-shadow:0 4px 8px rgba(0,0,0,.05); }
h1 { text-align:center; margin-bottom:20px; }
a { display:block; text-align:center; margin-bottom:15px; text-decoration:none; color:#06c; }
label { font-weight:bold; }
input[type="text"],input[type="number"] { width:100%; padding:8px; margin-top:5px; margin-bottom:15px; box-sizing:border-box; }
input[type="submit"] { width:100%; padding:10px; cursor:pointer; font-weight:bold; background:#06c; color:#fff; border:none; }
input[type="submit"]:hover { background:#049; }
.error { color:red; text-align:center; margin-bottom:15px; }
</style>
</head>
<body>

<div class="card">

<h1>Add New Program</h1>
<a href="program_list.php">Back to Programs</a>

<?php if ($error !== ''): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">

<label>Code</label>
<input type="text" name="code" value="<?= htmlspecialchars($codeValue) ?>" required>

<label>Title</label>
<input type="text" name="title" value="<?= htmlspecialchars($titleValue) ?>" required>

<label>Years</label>
<input type="number" name="years" value="<?= htmlspecialchars($yearsValue) ?>" required>

<input type="submit" value="Save">

</form>

</div>

</body>
</html>