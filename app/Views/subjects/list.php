<h1>Subjects</h1>

<div class="actions">
    <a href="index.php?controller=home&action=index">Back to Home</a>
    <?php if ($canManage): ?>
        | <a href="index.php?controller=subject&action=create">Add New Subject</a>
    <?php endif; ?>
</div>

<form method="get" class="search-form">
    <input type="hidden" name="controller" value="subject">
    <input type="hidden" name="action" value="list">

    <input type="text" name="search"
        value="<?= htmlspecialchars($searchText) ?>"
        placeholder="Search by subject code">

    <button class="btn">Search</button>
    <a class="reset-link" href="index.php?controller=subject&action=list">Reset</a>
</form>

<table>
    <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Unit</th>
        <?php if ($canManage): ?>
            <th>Action</th>
        <?php endif; ?>
    </tr>

    <?php if ($result->num_rows === 0): ?>
        <tr>
            <td colspan="<?= $canManage ? 4 : 3 ?>">No subjects found.</td>
        </tr>
    <?php else: ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['unit']) ?></td>
                <?php if ($canManage): ?>
                    <td>
                        <a href="index.php?controller=subject&action=edit&subject_id=<?= (int) $row['subject_id'] ?>">Edit</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>