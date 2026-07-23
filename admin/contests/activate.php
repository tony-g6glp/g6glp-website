<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');

$id = $_GET['id'] ?? 0;


$stmt = $pdo->prepare("
    UPDATE contests
    SET active = 1
    WHERE id = ?
");

$stmt->execute([$id]);


header(
    "Location: review.php?id=" . $id
);

exit;