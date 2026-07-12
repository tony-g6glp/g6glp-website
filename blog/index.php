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

<div class="blog-layout">

<aside class="blog-sidebar">

<?php include __DIR__ . '/../include/blog-sidebar.php'; ?>

</aside>


<main class="blog-content">

<?php if (empty($posts)): ?>

<p>No blog posts have been published yet.</p>

<?php else: ?>

<div class="cards">

<?php foreach ($posts as $post): ?>
<?php include __DIR__ . '/../include/blog-post-card.php'; ?>
<?php endforeach; ?>
</div>

<?php endif; ?>

</main>

</div>

</div>

<?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>