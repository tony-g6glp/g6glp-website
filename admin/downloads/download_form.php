<?php

$isEdit = !empty($download['id']);

?>

<div class="container">

<h2>

<?= $isEdit
        ? 'Edit Download'
        : 'Upload Download' ?>

</h2>

<form
    method="post"
    action="save.php"
    enctype="multipart/form-data">

<?= csrf_field(); ?>
<?php if ($isEdit): ?>

<input
type="hidden"
name="id"
value="<?= $download['id'] ?>">

<?php endif; ?>
<p>

<label>

Title<br>

<input
type="text"
name="title"
size="60"
value="<?= e($download['title'] ?? '') ?>"
required>

</label>

</p>

<p>

<label>

Version<br>

<input
    type="text"
    name="version"
    size="20"
	value="<?= e($download['version'] ?? '') ?>">

</label>

</p>

<p>

<label>

Category<br>

<select name="category_id">

<?php foreach ($categories as $category): ?>

<option
    value="<?= $category['id'] ?>"
    <?= ($category['id'] == ($download['category_id'] ?? 0))
        ? 'selected'
        : '' ?>
>
<?= e($category['name']) ?>
</option>

<?php endforeach; ?>

</select>

</label>

</p>

<p>

<label>

Description<br>

<textarea
    name="description"
    rows="6"
    cols="70"></textarea>

</label>

</p>

<p>

<label>

Access Level<br>

<select name="access_level">

<option
    value="<?= $category['id'] ?>"
    <?= ($category['id'] == ($download['category_id'] ?? 0))
        ? 'selected'
        : '' ?>
>
<?= e($category['name']) ?>
</option>

<option value="registered">
Registered Users
</option>

<option value="admin">
Administrators
</option>

</select>

</label>

</p>

<p>

<label>

<input
type="checkbox"
name="active"
value="1"

<?= ($download['active'] ?? 1)
        ? 'checked'
        : '' ?>>

Active

</label>

</p>

<?php if ($isEdit): ?>

<p>

Current File<br>

<strong>
<?= e($download['original_filename']) ?>
</strong>

</p>

<?php endif; ?>


<p>

<label>

<?= $isEdit
        ? 'Replace File (optional)'
        : 'Choose File' ?>

<br>

<input
    type="file"
    name="download_file"

    <?= $isEdit ? '' : 'required' ?>
>

</label>

</p>

<p>

<button type="submit">

<?= $isEdit
        ? 'Save Changes'
        : 'Upload' ?>

</button>

</p>

</form>

</div>