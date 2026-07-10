<?php
require_once __DIR__ . '/../../include/admin.php';

require_once __DIR__ . '/../../include/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);

if ($id < 1) {
    die('Invalid post');
}

$stmt = $pdo->prepare("
    DELETE FROM blog_posts
    WHERE id = ?
");

$stmt->execute([$id]);

header("Location: index.php");
exit;

if (!$id) {
    redirect('/admin/posts/');
}

// Load post to confirm it exists
$stmt = $pdo->prepare("
    SELECT id, title, featured_image
    FROM blog_posts
    WHERE id = ?
");

if (!$post) {
    redirect('/admin/posts/');
}

// Handle delete only via POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
verify_csrf();
	
// Get image filename first

$stmt->execute([$id]);

$post = $stmt->fetch();


// Remove image files if present
if (!empty($post['featured_image'])) {

    // Remove main image
    $image = __DIR__ . '/../../uploads/posts/' . $post['featured_image'];

    if (file_exists($image)) {
        unlink($image);
    }


    // Remove thumbnail
    $thumb = pathinfo($post['featured_image'], PATHINFO_FILENAME) . '_thumb.jpg';

    $thumb_file = __DIR__ . '/../../uploads/posts/thumbs/' . $thumb;

    if (file_exists($thumb_file)) {
        unlink($thumb_file);
    }
}


// Delete database row
$stmt = $pdo->prepare("
    DELETE FROM blog_posts
    WHERE id = ?
");

$stmt->execute([$id]);

redirect('/admin/posts/');

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Post</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h1>Delete Post</h1>

<p>Are you sure you want to delete:</p>

<h3><?= e($post['title']) ?></h3>

<form method="post" onSubmit="return confirm('Delete this post?');">

	<?= csrf_field(); ?>
    <button type="submit" class="danger">
        Yes, Delete
    </button>

    <a href="/g6glp/admin/posts/">Cancel</a>
</form>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>