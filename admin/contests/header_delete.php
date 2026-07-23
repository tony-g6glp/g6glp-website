<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;


$stmt = $pdo->prepare("
    SELECT contest_id
    FROM contest_headers
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch();


if (!$field) {
    die("Field not found");
}



// delete field
$stmt = $pdo->prepare("
    DELETE FROM contest_headers
	where id  = ?
");

$stmt->execute([$id]);


// return to wizard

header(
    "Location: headers.php?id=" . $field['contest_id']
);

exit;