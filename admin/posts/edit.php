<?php
require_once __DIR__ . '/../../include/admin.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/admin/posts/');
}

// Load existing post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    redirect('/admin/posts/');
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $category_id = !empty($_POST['category_id'])
		? $_POST['category_id']
		: null;

	$featured_image = $post['featured_image'];

	$new_image = upload_image($_FILES['featured_image'] ?? null);

	if ($new_image !== null) {

		// delete old image
		if (!empty($post['featured_image'])) {

    // remove main image
    $old_file = __DIR__ . '/../../uploads/posts/' . $post['featured_image'];

    if (file_exists($old_file)) {
        unlink($old_file);
    }


    // remove thumbnail
    $old_thumb = pathinfo(
        $post['featured_image'],
        PATHINFO_FILENAME
    ) . '_thumb.jpg';


    $old_thumb_file = __DIR__ . '/../../uploads/posts/thumbs/' . $old_thumb;


    if (file_exists($old_thumb_file)) {
        unlink($old_thumb_file);
    }
}

    $featured_image = $new_image;
}

    if ($title === '' || $content === '') {

        $message = "Title and content are required.";

    } else {
	
		// Create slug
        $slug = create_slug($title);

        // Update the post
        $stmt = $pdo->prepare("
            UPDATE blog_posts
            SET
                title = ?,
                slug = ?,
                content = ?,
                status = ?,
                category_id = ?, 
				featured_image = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $title,
            $slug,
            $content,
            $status,
            $category_id,
			$featured_image,
            $id
        ]);

        // Remove existing tags
        $stmt = $pdo->prepare("
            DELETE FROM post_tags
            WHERE post_id = ?
        ");

        $stmt->execute([$id]);

        // Save selected tags
        if (!empty($_POST['tags'])) {

            $stmt = $pdo->prepare("
                INSERT INTO post_tags (post_id, tag_id)
                VALUES (?, ?)
            ");

            foreach ($_POST['tags'] as $tag_id) {

                $stmt->execute([
                    $id,
                    $tag_id
                ]);
            }
        }

        redirect('/admin/posts/');
    }
}

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

$tag_stmt = $pdo->prepare("
    SELECT tag_id
    FROM post_tags
    WHERE post_id = ?
");

$tag_stmt->execute([$id]);

$post_tags = $tag_stmt->fetchAll(PDO::FETCH_COLUMN);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h1>Edit Post</h1>

<?php if ($message): ?>
    <p class="error"><?= e($message) ?></p>
<?php endif; 

include __DIR__ . '/post_form.php'; ?>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>