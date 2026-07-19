<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('edit_pages');

verify_csrf();


$access_level = $_POST['access_level'] ?? 'public';

$allowed_access = [
    'public',
    'registered',
    'admin'
];

if (!in_array($access_level, $allowed_access, true)) {
    $access_level = 'public';
}

$id = (int)($_POST['id'] ?? 0);

$title = trim($_POST['title'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$content = trim($_POST['content'] ?? '');

$sort_order = (int)($_POST['sort_order'] ?? 0);

$published = isset($_POST['published']) ? 1 : 0;

$show_in_menu = isset($_POST['show_in_menu']) ? 1 : 0;





if ($id < 1) {
    die('Invalid page');
}

if ($title === '' || $slug === '') {
    die('Title and slug are required');
}


$stmt = $pdo->prepare("
    UPDATE pages
	SET
		title = ?,
		slug = ?,
		content = ?,
		sort_order = ?,
		published = ?,
		show_in_menu = ?,
		access_level = ?
	WHERE id = ?
");

$stmt->execute([
    $title,
	$slug,
	$content,
	$sort_order,
	$published,
	$show_in_menu,
	$access_level,
	$id
]);


header("Location: index.php");
exit;