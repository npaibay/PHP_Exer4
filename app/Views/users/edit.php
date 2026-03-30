<h1>Edit User</h1>

<div class="actions">
    <a href="index.php?controller=user&action=list">Back to Users</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=user&action=update&id=<?= (int) $user['id'] ?>">
    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>">

    <label>Account Type</label>
    <select name="account_type">
        <?php foreach (($validRoles ?? ['admin', 'staff', 'teacher', 'student']) as $role): ?>
            <option value="<?= htmlspecialchars($role) ?>" <?= (($user['account_type'] ?? '') === $role) ? 'selected' : '' ?>>
                <?= htmlspecialchars($role) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn">Update User</button>
</form>