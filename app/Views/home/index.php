<h1>School Encoding Module</h1>

<p>
    Welcome, <strong><?= htmlspecialchars($user['username']) ?></strong>
    (<?= htmlspecialchars($user['account_type']) ?>)
</p>

<ul class="dashboard-links">
    <li><a href="index.php?controller=program&action=list">Programs</a></li>
    <li><a href="index.php?controller=subject&action=list">Subjects</a></li>

    <?php if (($user['account_type'] ?? '') === 'admin'): ?>
        <li><a href="index.php?controller=user&action=list">User Accounts</a></li>
    <?php endif; ?>

    <li><a href="index.php?controller=user&action=changePassword">Change Password</a></li>
    <li><a href="index.php?controller=auth&action=logout">Logout</a></li>
</ul>