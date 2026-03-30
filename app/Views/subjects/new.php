<h1>Add New Subject</h1>

<div class="actions">
    <a href="index.php?controller=subject&action=list">Back to Subjects</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="index.php?controller=subject&action=store">
    <label>Code</label>
    <input type="text" name="code" value="<?= htmlspecialchars($code ?? '') ?>">

    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($title ?? '') ?>">

    <label>Unit</label>
    <input type="number" name="unit" min="1" value="<?= htmlspecialchars($unit ?? '') ?>">

    <button class="btn">Save</button>
</form>