<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_downloads');


$stmt = $pdo->query("
    SELECT *
    FROM download_categories
    ORDER BY sort_order, name
");

$categories = $stmt->fetchAll();

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

<h2>Download Categories</h2>

<p>
<a href="new.php">New Category</a>
</p>

<?php if (!$categories): ?>

<p>No categories found.</p>
<div class="container">


<h1>Download Categories</h1>

<a href="new.php">
    Add Category
</a>
<?php else: ?>

<table>

<tr>
    <th>Name</th>
    <th>Slug</th>
    <th>Actions</th>
</tr

><?php foreach ($categories as $cat): ?>

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

<form method="post"
      action="delete.php"
      style="display:inline;">

<?= csrf_field(); ?>

<input
    type="hidden"
    name="id"
    value="<?= e($cat['id']) ?>"
>

<button
    class="link-button"
    type="submit"
    onclick="return confirm('Delete category?')">
    Delete
</button>

</form>
</td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>
</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>