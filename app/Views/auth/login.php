<div class="card">

<h1>Login Portal</h1>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=auth&action=login">
    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>">

    <label>Password</label>
    <input type="password" name="password">

    <input type="submit" value="Login">
</form>

<div class="hint">
    Default admin: 
    <span class="blur-text">admin / admin12345</span>
</div>

</div>