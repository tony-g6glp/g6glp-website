<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_tags');

$tags = $pdo->query("
    SELECT id, name, slug
    FROM tags
    ORDER BY name
")->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

<title>Tags</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h2>Tags</h2>

<p>
<a href="new.php">New Tag</a>
</p>

<table>

<tr>
<th>Name</th>
<th>Slug</th>
<th>Actions</th>
</tr>

<?php foreach ($tags as $tag): ?>

<tr>

<td>
<?= e($tag['name']) ?>
</td>

<td>
<?= e($tag['slug']) ?>
</td>

<td>

<a href="edit.php?id=<?= $tag['id'] ?>">
Edit
</a>

|

<form method="post"
      action="delete.php"
      style="display:inline;">

<?= csrf_field(); ?>

<input
    type="hidden"
    name="id"
    value="<?= e($tag['id']) ?>"
>

<button
    class="link-button"
    type="submit"
    onclick="return confirm('Delete tag?')">
    Delete
</button>

</form>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>