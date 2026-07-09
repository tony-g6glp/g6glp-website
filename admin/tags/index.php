<?php
require_once __DIR__ . '/../../include/admin.php';

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

<a href="delete.php?id=<?= $tag['id'] ?>"
onclick="return confirm('Delete tag?')">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>