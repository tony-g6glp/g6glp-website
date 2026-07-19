<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_downloads');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);

if ($id < 1) {
    die('Invalid post');
}


if (!$id) {
    redirect('/admin/download_categories/list.php');
}

// Check if category is in use

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM downloads
    WHERE category_id = ?
");

$stmt->execute([$id]);

$count = $stmt->fetchColumn();

if ($count > 0) {

    die("Cannot delete this category because downloads are using it.");

}

// Delete category

$stmt = $pdo->prepare("
    DELETE FROM download_categories
    WHERE id = ?
");

$stmt->execute([$id]);

redirect('/admin/download_categories/index.php');