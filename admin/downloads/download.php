<?php

require_once __DIR__ . '/../../include/bootstrap.php';


$id = (int)($_GET['id'] ?? 0);
if ($id < 1) {
    die('Invalid download');
}


// Get download information

$stmt = $pdo->prepare("
    SELECT *
    FROM downloads
    WHERE id = ?
    AND active = 1
");

$stmt->execute([$id]);

$file = $stmt->fetch();


if (!$file) {
    die('File not found');
}


// Check access

if ($file['access_level'] === 'registered') {

    if (empty($_SESSION['logged_in'])) {
        die('Login required');
    }

}


if ($file['access_level'] === 'admin') {

    if (
        empty($_SESSION['logged_in']) ||
        $_SESSION['role'] !== 'admin'
    ) {
        die('Administrator access required');
    }

}


// Physical file location

$path = __DIR__ .
    '/../../uploads/downloads/' .
    $file['storage_name'];


if (!file_exists($path)) {

    die('Missing file');

}


// Update statistics

$stmt = $pdo->prepare("
    UPDATE downloads
    SET download_count = download_count + 1,
        last_downloaded = NOW()
    WHERE id = ?
");

$stmt->execute([$id]);


// Send file

header(
    'Content-Type: ' . $file['mime_type']
);

header(
    'Content-Length: ' . filesize($path)
);

header(
    'Content-Disposition: attachment; filename="' .
    basename($file['original_filename']) .
    '"'
);


readfile($path);

exit;