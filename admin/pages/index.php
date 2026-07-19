<?php

require_once __DIR__ . '/../../include/admin.php';

if (
    !can('manage_pages') &&
    !can('create_pages') &&
    !can('edit_pages')
) {
    http_response_code(403);
    die('Access denied');
}

$stmt = $pdo->query("
    SELECT
        p.*,
        u.username
    FROM pages p

    LEFT JOIN admin_users u
        ON p.created_by = u.id

    ORDER BY
        p.sort_order,
        p.title
");

$pages = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

<title>Pages</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">

<h1>Pages</h1>

<p>
<a href="new.php">
Create New Page
</a>
</p>


<?php if (!$pages): ?>

<p>
No pages have been created.
</p>


<?php else: ?>


<table>

<tr>

<th>Title</th>
<th>Slug</th>
<th>Published</th>
<th>Author</th>
<th>Actions</th>

</tr>


<?php foreach ($pages as $page): ?>

<tr>

<td>
<?= e($page['title']) ?>
</td>

<td>
<?= e($page['slug']) ?>
</td>

<td>

<?= $page['published']
    ? 'Yes'
    : 'No'
?>

</td>

<td>
<?= e($page['username'] ?? '') ?>
</td>


<td>
<a href="/g6glp/page.php?slug=<?= e($page['slug']) ?>" target="_blank">
View
</a> | 
<a href="edit.php?id=<?= $page['id'] ?>">
Edit

|

<a href="delete.php?id=<?= $page['id'] ?>"
onclick="return confirm('Delete this page?');">
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