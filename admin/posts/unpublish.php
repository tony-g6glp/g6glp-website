<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('publish_posts');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    UPDATE blog_posts
    SET status='draft'
    WHERE id=?
");
$stmt->execute([$id]);

redirect('/admin/posts/');