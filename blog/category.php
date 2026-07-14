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
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
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
	AND archived = 0
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

<div class="blog-layout">

<aside class="blog-sidebar">

<?php include __DIR__ . '/../include/blog-sidebar.php'; ?>

</aside>


<main class="blog-content">



<h1><?= e($category['name'] ?? 'Category') ?></h1>


<?php foreach ($posts as $post): ?>
<?php include __DIR__ . '/../include/blog-post-card.php'; ?>
<?php endforeach; ?>


</div>

</main>

</div>

</div>

<?php include __DIR__ . '/../include/footer.php'; ?>


</body>
</html>