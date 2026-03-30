<h1>Programs</h1>

<div class="actions">
    <a href="index.php?controller=home&action=index">Back to Home</a>
    <?php if ($canManage): ?>
        | <a href="index.php?controller=program&action=create">Add New Program</a>
    <?php endif; ?>
</div>

<form method="get" class="search-form">
    <input type="hidden" name="controller" value="program">
    <input type="hidden" name="action" value="list">

    <input type="text" name="search"
        value="<?= htmlspecialchars($searchText) ?>"
        placeholder="Search by program code">

    <button class="btn">Search</button>
    <a class="reset-link" href="index.php?controller=program&action=list">Reset</a>
</form>

<table>
    <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Years</th>
        <?php if ($canManage): ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>

    <?php if ($result->num_rows === 0): ?>
        <tr>
            <td colspan="<?= $canManage ? 4 : 3 ?>">No programs found.</td>
        </tr>
    <?php else: ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['years']) ?></td>
                <?php if ($canManage): ?>
                    <td>
                        <a href="index.php?controller=program&action=edit&program_id=<?= (int) $row['program_id'] ?>">Edit</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>