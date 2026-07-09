<?php

require_once __DIR__ . '/../../include/admin.php';

$message = "";

// Handle form submission

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = $_POST['status'] ?? 'draft';

    if ($title === '' || $content === '') {
        $message = "Title and content are required.";
    } else {

        // Create slug
        $slug = create_slug($title);
		
		// upload image
		$featured_image = upload_image($_FILES['featured_image'] ?? null);
		
		// INSERT
        $stmt = $pdo->prepare("
            INSERT INTO blog_posts 
			(title, slug, content, status, category_id, featured_image, created_at)
			VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
		$category_id = !empty($_POST['category_id'])
			? $_POST['category_id']
			: null;
		$stmt->execute([
		$title,
		$slug,
		$content,
		$status,
		$category_id,
		$featured_image
	]);
$post_id = $pdo->lastInsertId();

if (!empty($_POST['tags'])) {

    $tag_stmt = $pdo->prepare("
        INSERT INTO post_tags (post_id, tag_id)
        VALUES (?, ?)
    ");

    foreach ($_POST['tags'] as $tag_id) {

        $tag_stmt->execute([
            $post_id,
            $tag_id
        ]);

    }
}	

        redirect('/admin/posts/');
    }
}

// Load categories

$categories = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name
")->fetchAll();

$tags = $pdo->query("
    SELECT id, name
    FROM tags
    ORDER BY name
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Post</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h1>New Post</h1>
<?php 
$post = [
    'title' => '',
    'content' => '',
    'status' => 'draft',
    'category_id' => null,
    'featured_image' => null
];
include __DIR__ . '/post_form.php'; ?>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>