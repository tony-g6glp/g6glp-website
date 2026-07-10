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
    redirect('/admin/categories/list.php');
}

// Check if category is in use

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM blog_posts
    WHERE category_id = ?
");

$stmt->execute([$id]);

$count = $stmt->fetchColumn();


if ($count > 0) {

    die("Cannot delete this category because posts are using it.");

}

// Delete category

$stmt = $pdo->prepare("
    DELETE FROM categories
    WHERE id = ?
");

$stmt->execute([$id]);

redirect('/admin/categories/index.php');