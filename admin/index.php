z<?php
require_once __DIR__ . '/../include/admin.php';

// Counts

$total_posts = $pdo->query("
    SELECT COUNT(*)
    FROM blog_posts
")->fetchColumn();


$published_posts = $pdo->query("
    SELECT COUNT(*)
    FROM blog_posts
    WHERE status = 'published'
")->fetchColumn();


$draft_posts = $pdo->query("
    SELECT COUNT(*)
    FROM blog_posts
    WHERE status = 'draft'
")->fetchColumn();


$total_categories = $pdo->query("
    SELECT COUNT(*)
    FROM categories
")->fetchColumn();


$total_tags = $pdo->query("
    SELECT COUNT(*)
    FROM tags
")->fetchColumn();

$posts = $pdo->query("
    SELECT id, title, status
    FROM blog_posts
    ORDER BY created_at DESC
    LIMIT 4
")->fetchAll();

$latest_posts = $pdo->query("
    SELECT id, title, status, created_at
    FROM blog_posts
    ORDER BY created_at DESC
    LIMIT 4
")->fetchAll();

$draft_list = $pdo->query("
    SELECT id, title, created_at
    FROM blog_posts
    WHERE status = 'draft'
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll();

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

<p>Drafts: <?= e($draft_posts) ?></p>

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
        <a href="posts/edit.php?id=<?= $post['id'] ?>">
        <?= e($post['title']) ?>
        </a>
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
        

        <?php if (empty($draft_list)): ?>

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


        <?php foreach ($draft_list as $draft): ?>

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

<?php foreach ($posts as $p): ?>

<div class="card">

<h3><?= e($p['title']) ?></h3>

<p>Status: <?= e($p['status']) ?></p>

<a href="posts/edit.php?id=<?= $p['id'] ?>">
Edit
</a>

|

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
 </div>

<?php endforeach; ?>

</div> 








<?php include __DIR__ . '/../include/footer.php'; ?>
</body>
</html>