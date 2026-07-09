<?php
require_once __DIR__ . '/../../include/admin.php';

$id = $_GET['id'] ?? null;

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