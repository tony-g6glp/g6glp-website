<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('delete_pages');


$id = (int)($_GET['id'] ?? 0);


if ($id < 1) {

    die('Invalid page');

}


$stmt = $pdo->prepare("
    DELETE FROM pages
    WHERE id = ?
");


$stmt->execute([$id]);


header("Location: index.php");

exit;