<?php
require_once __DIR__ . '/../include/bootstrap.php';


$category_slug = $_GET['category'] ?? '';

$tags = $_GET['tags'] ?? [];



$sql = "
    SELECT DISTINCT
        blog_posts.*,
        categories.name AS category_name,
        categories.slug AS category_slug

    FROM blog_posts

    LEFT JOIN categories
    ON blog_posts.category_id = categories.id

";


$params = [];



/*
    Join tags only if filtering by tags
*/

if (!empty($tags)) {

    $sql .= "
        JOIN post_tags
        ON blog_posts.id = post_tags.post_id

        JOIN tags
        ON post_tags.tag_id = tags.id
    ";

}



/*
    Always hide drafts
*/

$sql .= "
    WHERE blog_posts.status = 'published'
	AND blog_posts.archived = 0
";



/*
    Category filter
*/

if ($category_slug !== '') {

    $sql .= "
        AND categories.slug = ?
    ";

    $params[] = $category_slug;

}



/*
    Tag filter
*/

if (!empty($tags)) {

    $placeholders = implode(',', array_fill(0, count($tags), '?'));

    $sql .= "
        AND tags.slug IN ($placeholders)
    ";

    foreach ($tags as $tag) {
        $params[] = $tag;
    }

}



$sql .= "
    ORDER BY blog_posts.created_at DESC
";



$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$posts = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html>

<head>

<title>
Filtered Posts
</title>

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


<h1>
Filtered Posts
</h1>


<?php if (empty($posts)): ?>

<p>
No published posts found.
</p>


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