<?php
require_once __DIR__ . '/../include/bootstrap.php';

$posts = $pdo->query("
    SELECT 
        blog_posts.*,
        categories.name AS category_name,
        categories.slug AS category_slug
    FROM blog_posts
    LEFT JOIN categories
    ON blog_posts.category_id = categories.id
    WHERE blog_posts.status='published'
    ORDER BY blog_posts.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>

<title>G6GLP Blog</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../include/public-header.php'; ?>

<?php include __DIR__ . '/../include/public-nav.php'; ?>

<div class="container">

<?php if (empty($posts)): ?>
<p>No blog posts have been published yet.</p>

<?php else: ?>

<div class="cards">

<?php foreach ($posts as $post): ?>

<div class="card">

<?php if (!empty($post['featured_image'])): ?>

<?php
$thumb = pathinfo(
    $post['featured_image'],
    PATHINFO_FILENAME
) . '_thumb.jpg';
?>

<img
src="/g6glp/uploads/posts/thumbs/<?= e($thumb) ?>"
alt="<?= e($post['title']) ?>"
class="post-thumb"
>

<?php endif; ?>


<h2><?= e($post['title']) ?></h2>
<p>
Category:

<?php if (!empty($post['category_name'])): ?>

<a href="category.php?slug=<?= urlencode($post['category_slug']) ?>">
    <?= e($post['category_name']) ?>
</a>

<?php else: ?>

Uncategorised

<?php endif; ?>

</p>
<p>
<?= nl2br(e(substr($post['content'], 0, 250))) ?>
<?php if (strlen($post['content']) > 250): ?>...<?php endif; ?>
</p>

<p>
<small>
Published:
<?= e(date('d M Y', strtotime($post['created_at']))) ?>
</small>
</p>

<p>
<a href="post.php?slug=<?= urlencode($post['slug']) ?>">
Read more...
</a>
</p>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

<?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>