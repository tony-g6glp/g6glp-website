<?php
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
    redirect('/admin/tags/index.php');
}


// Remove tag links from posts

$stmt = $pdo->prepare("
    DELETE FROM post_tags
    WHERE tag_id = ?
");

$stmt->execute([$id]);


// Remove the tag itself

$stmt = $pdo->prepare("
    DELETE FROM tags
    WHERE id = ?
");

$stmt->execute([$id]);


redirect('/admin/tags/index.php');