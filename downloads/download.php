<?php

require_once __DIR__ . '/../include/bootstrap.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT *
    FROM downloads
    WHERE id = ?
    AND active = 1
");

$stmt->execute([$id]);

$file = $stmt->fetch();

if (!$file) {
    $error_code = 404;
	$error_title = 'Download Not Found';
	$error_message = 'The requested download could not be found or has been removed.';
	
	include __DIR__ . '/../include/error_page.php';
	exit;
}


// Check access

if ($file['access_level'] === 'registered') {

    if (empty($_SESSION['logged_in'])) {

        $error_code = 403;
        $error_title = 'Login Required';
        $error_message = 'This download is available only to registered users. Please log in to continue.';

        include __DIR__ . '/../include/error_page.php';
        exit;
    }
}


if ($file['access_level'] === 'admin') {

    if (
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {

        $error_code = 403;
        $error_title = 'Access Restricted';
        $error_message = 'This download is available only to site administrators.';

        include __DIR__ . '../include/error_page.php';
        exit;
    }
}

// Send file

// Send file

$path = __DIR__ . "/../uploads/downloads/" . $file['storage_name'];

if (!file_exists($path)) {
    $error_code = 404;
		$error_title = 'Download Not Found';
		$error_message = 'The requested download could not be found or has been removed.';
		
		include __DIR__ . '../include/error_page.php';
		exit;
}

// Update download statistics

$stmt = $pdo->prepare("
    UPDATE downloads
    SET
        download_count = download_count + 1,
        last_downloaded = NOW()
    WHERE id = ?
");

$stmt->execute([$id]);
header("Content-Disposition: attachment; filename=\"" . $file['original_filename'] . "\"");
header("Content-Type: application/octet-stream");

readfile($path);
exit;