<h1>User Accounts</h1>

<div class="actions">
    <a href="index.php?controller=home&action=index">Back to Home</a> |
    <a href="index.php?controller=user&action=create">Add New User</a>
</div>

<table>
    <tr>
        <th>Username</th>
        <th>Account Type</th>
        <th>Created On</th>
        <th>Updated On</th>
        <th>Action</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['account_type']) ?></td>
                <td><?= htmlspecialchars($row['created_on']) ?></td>
                <td><?= htmlspecialchars($row['updated_on'] ?? '') ?></td>
                <td>
                    <a href="index.php?controller=user&action=edit&id=<?= (int) $row['id'] ?>">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No users found.</td>
        </tr>
    <?php endif; ?>
</table>