<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_media');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    die('Invalid request');

}


verify_csrf();


$id = (int)($_POST['id'] ?? 0);


if ($id < 1) {

    die('Invalid media id');

}


// Get file details first

$stmt = $pdo->prepare("
    SELECT filename
    FROM media
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$media = $stmt->fetch();


if (!$media) {

    die('Media not found');

}


$filename = $media['filename'];

$stmt = $pdo->prepare("
    SELECT title
	FROM blog_posts
	WHERE featured_image = ?
	LIMIT 1
	");

$stmt->execute([$filename]);

$used = $stmt->fetchColumn();


if ($used > 0) {

    $_SESSION['message'] = "Delete blocked: this image is attached to an existing blog post.";

    redirect('/admin/media/');

}

// Delete physical file

$file = __DIR__ . '/../../uploads/posts/' . $filename;

if (file_exists($file)) {

    unlink($file);

}


// Delete thumbnail

$thumb = __DIR__ . '/../../uploads/posts/thumbs/' .
         pathinfo($filename, PATHINFO_FILENAME) .
         '_thumb.jpg';


if (file_exists($thumb)) {

    unlink($thumb);

}


// Delete database record

$stmt = $pdo->prepare("
    DELETE FROM media
    WHERE id = ?
");

$stmt->execute([$id]);


redirect('/admin/media/');
