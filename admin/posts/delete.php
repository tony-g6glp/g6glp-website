<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('delete_posts');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}


verify_csrf();


$id = (int)($_POST['id'] ?? 0);


if ($id < 1) {
    die('Invalid post');
}


// Load post first so we know the image filename

$stmt = $pdo->prepare("
    SELECT id, featured_image
    FROM blog_posts
    WHERE id = ?
");

$stmt->execute([$id]);

$post = $stmt->fetch();


if (!$post) {
    redirect('/admin/posts/');
}


// Delete featured image

if (!empty($post['featured_image'])) {

    $image = __DIR__ . '/../../uploads/posts/' . $post['featured_image'];

    if (file_exists($image)) {
        unlink($image);
    }


    $thumb = pathinfo(
        $post['featured_image'],
        PATHINFO_FILENAME
    ) . '_thumb.jpg';


    $thumb_file = __DIR__ . '/../../uploads/posts/thumbs/' . $thumb;

    if (file_exists($thumb_file)) {
        unlink($thumb_file);
    }

}


// Remove tags

$stmt = $pdo->prepare("
    DELETE FROM post_tags
    WHERE post_id = ?
");

$stmt->execute([$id]);


// Delete post

$stmt = $pdo->prepare("
    DELETE FROM blog_posts
    WHERE id = ?
");

$stmt->execute([$id]);


redirect('/admin/posts/');
?>
