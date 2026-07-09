<?php
require_once __DIR__ . '/../include/bootstrap.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
    SELECT 
        blog_posts.id,
        blog_posts.title,
        blog_posts.slug,
        blog_posts.content,
        blog_posts.created_at,
        blog_posts.featured_image,
        categories.name AS category_name
    FROM blog_posts
    LEFT JOIN categories
    ON blog_posts.category_id = categories.id
    WHERE blog_posts.slug = ?
    LIMIT 1
");

$stmt->execute([$slug]);

$post = $stmt->fetch();

if (!$post) {
    die("Post not found");
}

$tag_stmt = $pdo->prepare("
    SELECT 
        tags.name,
        tags.slug
    FROM tags
    JOIN post_tags
    ON tags.id = post_tags.tag_id
    WHERE post_tags.post_id = ?
    ORDER BY tags.name
");

$tag_stmt->execute([$post['id']]);



$post_tags = $tag_stmt->fetchAll();




?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= e($post['title']) ?></title>
<link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<header>
<header class="post-header">


<?php include __DIR__ . '/../include/public-header.php'; ?>
<?php include __DIR__ . '/../include/public-nav.php'; ?>

<div class="container">


<?php if (!empty($post['featured_image'])): ?>
<?php
$thumb = pathinfo($post['featured_image'], PATHINFO_FILENAME) . '_thumb.jpg';
?>
<img
src="/g6glp/uploads/posts/thumbs/<?= e($thumb)  ?>"
alt="<?= e($post['title']) ?>" 
>

<?php endif; ?></div></div>
</header>

<nav>
<a href="index.php">Back to Blog</a>
</nav>

<div class="container">

<div class="card">

<p>
Category:
<?= e($post['category_name'] ?? 'Uncategorised') ?>
</p>


<?php if (!empty($post_tags)): ?>

<p>
Tags:

<?php foreach ($post_tags as $tag): ?>

<a href="tag.php?slug=<?= urlencode($tag['slug']) ?>">
<?= e($tag['name']) ?> |
</a>

<?php endforeach; ?>

</p>

<?php endif; ?>


<p>
<?= nl2br(e($post['content'])) ?>
</p>

</div>


</body>
</html>
