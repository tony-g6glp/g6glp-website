<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('create_pages');

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

$title = trim($_POST['title'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$content = trim($_POST['content'] ?? '');

$sort_order = (int)($_POST['sort_order'] ?? 0);

$published = isset($_POST['published']) ? 1 : 0;

$show_in_menu = isset($_POST['show_in_menu']) ? 1 : 0;

if ($title === '' || $slug === '') {

    die('Title and slug required');

}


$stmt = $pdo->prepare("

INSERT INTO pages
(
    title,
    slug,
    content,
    published,
	show_in_menu,
	sort_order,
	created_by
	)

VALUES
(
    ?,
    ?,
    ?,
    ?,
    ?,
	?,
    ?
)

");


$stmt->execute([

    $title,
	$slug,
	$content,
	$published,
	$show_in_menu,
	$sort_order,
	$_SESSION['user_id']

]);


header("Location: index.php");

exit;