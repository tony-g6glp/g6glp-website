<?php
require_once __DIR__ . '/../../include/admin.php';

$id = $_GET['id'] ?? null;

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