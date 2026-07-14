<?php

require_once __DIR__ . '/../include/admin.php';


// -------------------------
// Dashboard Counts
// -------------------------

$total_posts = $pdo->query("
    SELECT COUNT(*)
    FROM blog_posts
")->fetchColumn();


$published_posts = $pdo->query("
    SELECT COUNT(*)
    FROM blog_posts
    WHERE status = 'published'
")->fetchColumn();


// -------------------------
// Draft Posts
// -------------------------

if (can('edit_posts')) {

    $draft_posts = $pdo->query("
        SELECT id, title, created_at, created_by
        FROM blog_posts
        WHERE status = 'draft'
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll();

} else {

    $stmt = $pdo->prepare("
        SELECT id, title, created_at, created_by
        FROM blog_posts
        WHERE status = 'draft'
        AND created_by = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $draft_posts = $stmt->fetchAll();

}


// -------------------------
// Other Counts
// -------------------------

$total_categories = $pdo->query("
    SELECT COUNT(*)
    FROM categories
")->fetchColumn();


$total_tags = $pdo->query("
    SELECT COUNT(*)
    FROM tags
")->fetchColumn();


// -------------------------
// Latest Posts
// -------------------------

if (can('edit_posts')) {

    $latest_posts = $pdo->query("
        SELECT id, title, status, created_at, created_by
        FROM blog_posts
        ORDER BY created_at DESC
        LIMIT 4
    ")->fetchAll();

} else {

    $stmt = $pdo->prepare("
        SELECT id, title, status, created_at, created_by
        FROM blog_posts
        WHERE created_by = ?
        ORDER BY created_at DESC
        LIMIT 4
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

    $latest_posts = $stmt->fetchAll();

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="/g6glp/include/css.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include __DIR__ . '/../include/header.php'; ?>
<?php include __DIR__ . '/../include/nav.php'; ?>


<div class="container">


<div class="cards">


<div class="card">

<h3>Posts</h3>

<p>Total: <?= e($total_posts) ?></p>


<p>Published: <?= e($published_posts) ?></p>

<p>Drafts: <?= count($draft_posts) ?></p>

</div>


<div class="card">

<h3>Categories</h3>

<p><?= e($total_categories) ?></p>

</div>


<div class="card">

<h3>Tags</h3>

<p><?= e($total_tags) ?></p>

</div>


</div>

<h2>Quick Actions</h2>

<div class="cards">

<div class="card">

<h3>Posts</h3>

<p>
<a href="posts/new.php">
New Post
</a>
</p>

<p>
<a href="posts/index.php">
Manage Posts
</a>
</p>

</div>


<div class="card">

<h3>Categories</h3>

<p>
<a href="categories/new.php">
New Category
</a>
</p>

<p>
<a href="categories/index.php">
Manage Categories
</a>
</p>

</div>


<div class="card">

<h3>Tags</h3>

<p>
<a href="tags/new.php">
New Tag
</a>
</p>

<p>
<a href="tags/index.php">
Manage Tags
</a>
</p>

</div>


</div> 
<br>
<div class="dashboard-column">

<div class="cards">


    <div class="card"> 
		<h2>Latest Posts</h2>
       

        <table>

        <?php foreach ($latest_posts as $post): ?>

        <tr>

        <td>
        <?php if (can_edit_post($post)): ?>

			<a href="posts/edit.php?id=<?= $post['id'] ?>">
			<?= e($post['title']) ?>
			</a>
			
			<?php else: ?>
			
			<?= e($post['title']) ?>
			
			<?php endif; ?>
        </td>

        <td>
        <?= e($post['status']) ?>
        </td>

        <td>
        <?= e($post['created_at']) ?>
        </td>

        </tr>

        <?php endforeach; ?>

        </table>

    </div>


    <div class="card">
		<h2>Drafts Needing Attention</h2>
        

        <?php if (empty($draft_posts)): ?>

        <p>
        No drafts waiting.
        </p>

        <?php else: ?>

        <table>

        <tr>
            <th>Title</th>
            <th>Created</th>
            <th></th>
        </tr>


        <?php foreach ($draft_posts as $draft): ?>

        <tr>

        <td>
        <?= e($draft['title']) ?>
        </td>

        <td>
        <?= e($draft['created_at']) ?>
        </td>

        <td>
        <a href="posts/edit.php?id=<?= $draft['id'] ?>">
        Edit
        </a>
        </td>

        </tr>

        <?php endforeach; ?>

        </table>

        <?php endif; ?>

    </div>


</div> 

</div>

<h2>Recent Posts</h2>
<div class="cards">

<?php foreach ($latest_posts as $p): ?>

<div class="card">

<h3><?= e($p['title']) ?></h3>

<p>Status: <?= e($p['status']) ?></p>

	<?php if (
		can('edit_posts') ||
		(
			can('edit_own_posts') &&
			$p['created_by'] == $_SESSION['user_id']
		)
	): ?>

<a href="posts/edit.php?id=<?= $p['id'] ?>">
Edit
</a>

<?php endif; ?>


<?php if (can_delete_post($post)): ?>

<form method="post" action="posts/delete.php" style="display:inline;">

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

<?php endif; ?>
 </div>

<?php endforeach; ?>

</div> 








<?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>