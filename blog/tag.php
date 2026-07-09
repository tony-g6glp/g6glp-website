<?php

require_once __DIR__ . '/../include/bootstrap.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
    SELECT name
    FROM tags
    WHERE slug = ?
");

$stmt->execute([$slug]);

$tag = $stmt->fetch();

if (!$tag) {
    die("Tag not found");
}


$stmt = $pdo->prepare("
    SELECT
		blog_posts.id,
		blog_posts.title,
		blog_posts.slug,
		blog_posts.content,
		blog_posts.featured_image,
		blog_posts.created_at
		FROM blog_posts
    JOIN post_tags
    ON blog_posts.id = post_tags.post_id
    JOIN tags
    ON post_tags.tag_id = tags.id
    WHERE tags.slug = ?
    AND blog_posts.status = 'published'
    ORDER BY blog_posts.created_at DESC
");

$stmt->execute([$slug]);

$posts = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>

<head>
<title>
Tag: <?= e($tag['name']) ?>
</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../include/public-header.php'; ?>

<?php include __DIR__ . '/../include/public-nav.php'; ?>


<div class="container">

<h2>
Tag: <?= e($tag['name']) ?>
</h2>
<?php if (!$posts): ?>

<p>No published posts found for this tag.</p>

<?php else: ?>

<?php foreach ($posts as $post): ?>

<div class="card">

<h3>
<a href="post.php?slug=<?= urlencode($post['slug']) ?>">
<?= e($post['title']) ?>
</a>
</h3>

<p>
<?= e($post['created_at']) ?>
</p>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

<?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>