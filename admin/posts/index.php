<?php
require_once __DIR__ . '/../../include/admin.php';

$posts = $pdo->query("
    SELECT 
        blog_posts.*,
        categories.name AS category_name
    FROM blog_posts
    LEFT JOIN categories
    ON blog_posts.category_id = categories.id
    ORDER BY blog_posts.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h1>Posts</h1>

<a href="new.php">+ New Post</a>

<br><br>

<?php if (!$posts): ?>
    <p>No posts found.</p>
<?php else: ?>

<table>
    <tr>
        <th>Title</th>
		<th>Category</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($posts as $p): ?>
        <tr>
		
            <td><?= e($p['title']) ?></td>

			<td>
			<?= e($p['category_name'] ?? 'None') ?>
			</td>
			
			<td>
			<?php if ($p['status'] == 'published'): ?>
				<span class="published">Published</span>
			<?php else: ?>
				<span class="draft">Draft</span>
			<?php endif; ?>
			</td>
			
			<td>
			<?= e(date('d M Y', strtotime($p['created_at']))) ?>
			</td>
            
			<td>
                <a href="edit.php?id=<?= $p['id'] ?>">Edit</a> |

    <?php if ($p['status'] == 'draft'): ?>
        <a href="publish.php?id=<?= $p['id'] ?>">Publish</a> |
    <?php else: ?>
        <a href="unpublish.php?id=<?= $p['id'] ?>">Unpublish</a> |
    <?php endif; ?>

    	<a href="delete.php?id=<?= $p['id'] ?>"
				onclick="return confirm('Delete post?')">
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