<h1>Change Password</h1>

<div class="actions">
    <a href="index.php?controller=home&action=index">Back to Home</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=user&action=updatePassword">
    <label>Current Password</label>
    <input type="password" name="current_password">

    <label>New Password</label>
    <input type="password" name="new_password">

    <label>Confirm New Password</label>
    <input type="password" name="confirm_new_password">

    <button class="btn">Update Password</button>
</form>