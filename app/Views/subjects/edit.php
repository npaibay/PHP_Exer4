<h1>Edit Subject</h1>

<div class="actions">
    <a href="index.php?controller=subject&action=list">Back to Subjects</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=subject&action=update&subject_id=<?= (int) $subject['subject_id'] ?>">
    <label>Code</label>
    <input type="text" name="code" value="<?= htmlspecialchars($subject['code']) ?>">

    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($subject['title']) ?>">

    <label>Unit</label>
    <input type="number" name="unit" min="1" value="<?= htmlspecialchars($subject['unit']) ?>">

    <button class="btn">Update</button>
</form>