<?php
require_once __DIR__ . '/../../include/admin.php';

if (
    !can('edit_posts') &&
    !can('edit_own_posts')
) {

    http_response_code(403);
    die('Access denied');

}

if (can('edit_posts')) {

    // Admin and Editor see all posts

    $posts = $pdo->query("
        SELECT 
            blog_posts.*,
            categories.name AS category_name
        FROM blog_posts
        LEFT JOIN categories
        ON blog_posts.category_id = categories.id
        ORDER BY blog_posts.created_at DESC
    ")->fetchAll();


} else {

    // Authors only see their own posts

    $stmt = $pdo->prepare("
        SELECT 
            blog_posts.*,
            categories.name AS category_name
        FROM blog_posts
        LEFT JOIN categories
        ON blog_posts.category_id = categories.id
        WHERE blog_posts.created_by = ?
        ORDER BY blog_posts.created_at DESC
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $posts = $stmt->fetchAll();

}

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
                <?php if (can_edit_post($post)):if (
					can('edit_posts') ||
					(
						can('edit_own_posts') &&
						$p['created_by'] == $_SESSION['user_id']
					)): ?>
				
				<a href="edit.php?id=<?= $p['id'] ?>">
					Edit
				</a>
				
				<?php endif; ?>  |

    <?php if ($p['status'] == 'draft'): ?>
        <a href="publish.php?id=<?= $p['id'] ?>">Publish</a> |
    <?php else: ?>
        <a href="unpublish.php?id=<?= $p['id'] ?>">Unpublish</a> |
    <?php endif; ?>

    	<form method="post" action="delete.php" style="display:inline;">

<?= csrf_field(); ?>

<input
    type="hidden"
    name="id"
    value="<?= e($p['id']) ?>"
>

<button
    type="submit"
    onclick="return confirm('Delete post?')">
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