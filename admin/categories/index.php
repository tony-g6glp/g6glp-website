<?php
require_once __DIR__ . '/../../include/admin.php';

$categories = $pdo->query("
    SELECT id, name, slug
    FROM categories
    ORDER BY name
")->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>
<title>Categories</title>
<link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h2>Categories</h2>

<p>
<a href="new.php">New Category</a>
</p>

<?php if (!$categories): ?>

<p>No categories found.</p>

<?php else: ?>

<table>

<tr>
    <th>Name</th>
    <th>Slug</th>
    <th>Actions</th>
</tr>

<?php foreach ($categories as $cat): ?>

<tr>

<td>
<?= e($cat['name']) ?>
</td>

<td>
<?= e($cat['slug']) ?>
</td>

<td>
<a href="edit.php?id=<?= $cat['id'] ?>">Edit</a>
|
<a href="delete.php?id=<?= $cat['id'] ?>"
onclick="return confirm('Delete category?')">
Delete
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>
</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>