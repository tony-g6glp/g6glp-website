<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;


$stmt = $pdo->prepare("
    SELECT contest_id
    FROM contest_fields
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch();


if (!$field) {
    die("Field not found");
}


// delete options first

$stmt = $pdo->prepare("
    DELETE FROM contest_field_options
    WHERE field_id = ?
");

$stmt->execute([$id]);


// delete field

$stmt = $pdo->prepare("
    DELETE FROM contest_fields
    WHERE id = ?
");

$stmt->execute([$id]);


// return to wizard

header(
    "Location: fields.php?id=" . $field['contest_id']
);

exit;