<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('edit_pages');


$id = (int)($_GET['id'] ?? 0);


$stmt = $pdo->prepare("
    SELECT *
    FROM pages
    WHERE id = ?
");

$stmt->execute([$id]);

$page = $stmt->fetch();


if (!$page) {

    die('Page not found');

}


include 'page_form.php';