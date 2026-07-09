<?php
require_once __DIR__ . '/../../include/admin.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    UPDATE blog_posts
    SET status='published'
    WHERE id=?
");
$stmt->execute([$id]);

redirect('/admin/posts/');