<?php
require_once __DIR__ . '/../include/bootstrap.php';

$slug = $_GET['slug'] ?? '';
$cat = $pdo->prepare("
    SELECT name
    FROM categories
    WHERE slug = ?
");

$cat->execute([$slug]);

$category = $cat->fetch();

if (!$category) {
    die("Category not found");
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
    JOIN categories
    ON blog_posts.category_id = categories.id
    WHERE categories.slug = ?
    AND blog_posts.status='published'
    ORDER BY blog_posts.created_at DESC
");

$stmt->execute([$slug]);

$posts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

<title>Category</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>
<?php include __DIR__ . '/../include/public-header.php'; ?>

<?php include __DIR__ . '/../include/public-nav.php'; ?>


<div class="container">

<h1><?= e($category['name'] ?? 'Category') ?></h1>


<?php foreach ($posts as $post): ?>

<div class="card">

<h2>
<?= e($post['title']) ?>
</h2>

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
<p>
<?= nl2br(e(substr($post['content'],0,250))) ?>

<?php if (strlen($post['content']) > 250): ?>
...
</p>


<a href="post.php?slug=<?= urlencode($post['slug']) ?>">
Read more
</a>
<?php endif; ?>

</div>

<?php endforeach; ?>


</div>


<?php include __DIR__ . '/../include/footer.php'; ?>


</body>
</html>