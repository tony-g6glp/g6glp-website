<!DOCTYPE html>
<html>

<head>

<title>
<?= empty($page['id'])
    ? 'New Page'
    : 'Edit Page'
?>
</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>


<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<h1>
<?= empty($page['id'])
    ? 'Create Page'
    : 'Edit Page'
?>
</h1>


<form method="post" action="<?= empty($page['id']) ? 'save.php' : 'update.php' ?>">

<?= csrf_field(); ?>

<?php if (!empty($page['id'])): ?>

<input
    type="hidden"
    name="id"
    value="<?= $page['id'] ?>">

<?php endif; ?>

<p>

<label>

Title<br>

<input
type="text"
name="title"
size="60"
value="<?= e($page['title']) ?>"
required>

</label>

</p>


<p>

<label>

Slug<br>

<input
type="text"
name="slug"
size="60"
value="<?= e($page['slug']) ?>"
required>

</label>

</p>


<p>

<label>

Content<br>

<textarea
    id="editor"
    name="content"
    rows="20"
    cols="80"><?= e($page['content']) ?></textarea>
</label>

</p>


<p>

<label>

Sort Order<br>

<input
type="number"
name="sort_order"
value="<?= e($page['sort_order']) ?>">

</label>

</p>

<p>

<label>

Access Level<br>

<select name="access_level">

<option value="public"
<?= $page['access_level'] === 'public'
    ? 'selected'
    : ''
?>>
Public
</option>


<option value="registered"
<?= $page['access_level'] === 'registered'
    ? 'selected'
    : ''
?>>
Registered Users
</option>


<option value="admin"
<?= $page['access_level'] === 'admin'
    ? 'selected'
    : ''
?>>
Administrators
</option>

</select>

</label>

</p>
<p>

<label>

<input
type="checkbox"
name="published"
value="1"
<?= $page['published']
    ? 'checked'
    : ''
?>>

Published

</label>

</p>
<p>

<label>

<input
type="checkbox"
name="show_in_menu"
value="1"
<?= $page['show_in_menu']
    ? 'checked'
    : ''
?>>

Show in navigation

</label>

</p>

<button type="submit">
Save Page
</button>


</form>


</div>

<script src="/g6glp/include/js/tinymce/tinymce.min.js"></script>

<script>

console.log(tinymce);

tinymce.init({

    selector: '#editor',

    license_key: 'gpl',

    height: 500,

    menubar: true,

    plugins:
        'lists link image table code fullscreen searchreplace wordcount',

    toolbar:
        'undo redo | styles | bold italic underline | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist | link image table | code fullscreen'

});

</script>
<?php include __DIR__ . '/../../include/footer.php'; ?>
</body>

</html>