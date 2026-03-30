<h1>Add New User</h1>

<div class="actions">
    <a href="index.php?controller=user&action=list">Back to Users</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=user&action=store">
    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>">

    <label>Account Type</label>
    <select name="account_type">
        <?php foreach (($validRoles ?? ['admin', 'staff', 'teacher', 'student']) as $role): ?>
            <option value="<?= htmlspecialchars($role) ?>" <?= (($accountType ?? 'student') === $role) ? 'selected' : '' ?>>
                <?= htmlspecialchars($role) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Password</label>
    <input type="password" name="password">

    <label>Confirm Password</label>
    <input type="password" name="confirm_password">

    <button class="btn">Create User</button>
</form>