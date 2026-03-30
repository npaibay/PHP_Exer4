<h1>Edit Program</h1>

<div class="actions">
    <a href="index.php?controller=program&action=list">Back to Programs</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=program&action=update&program_id=<?= (int) $program['program_id'] ?>">
    <label>Code</label>
    <input type="text" name="code" value="<?= htmlspecialchars($program['code']) ?>">

    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($program['title']) ?>">

    <label>Years</label>
    <input type="number" name="years" min="1" max="6" value="<?= htmlspecialchars($program['years']) ?>">

    <button class="btn">Update</button>
</form>