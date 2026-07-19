<?php

require_once __DIR__ . '/../../include/admin.php';

$id = (int)($_POST['id'] ?? 0);


if ($id == 0) {

    require_permission('create_downloads');

} else {

    if (!can('edit_downloads')) {

        $stmt = $pdo->prepare("
            SELECT uploaded_by
            FROM downloads
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $owner = $stmt->fetchColumn();


        if (
            !can('edit_own_downloads')
            ||
            $owner != $_SESSION['user_id']
        ) {
            http_response_code(403);
            die('Access denied');
        }

    }

}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);

// Check file upload

// New upload must include a file

if (
    $id == 0 &&
    (
        !isset($_FILES['download_file']) ||
        $_FILES['download_file']['error'] !== UPLOAD_ERR_OK
    )
) {
    die('File upload failed');
}


// Form data

$title = trim($_POST['title'] ?? '');

$version = trim($_POST['version'] ?? '');

$category_id = (int)($_POST['category_id'] ?? 0);

$description = trim($_POST['description'] ?? '');

$access_level = $_POST['access_level'] ?? 'public';

$active = isset($_POST['active']) ? 1 : 0;



if ($title === '') {
    die('Title is required');
}


if ($category_id < 1) {
    die('Invalid category');
}


// Check access level

$allowed_access = [
    'public',
    'registered',
    'admin'
];

if (!in_array($access_level, $allowed_access, true)) {
    $access_level = 'public';
}

if ($id > 0) {

    // Update all text fields

    $stmt = $pdo->prepare("
        UPDATE downloads
        SET
            category_id=?,
            title=?,
            version=?,
            description=?,
            access_level=?,
            active=?
        WHERE id=?
    ");

    $stmt->execute([
        $category_id,
        $title,
        $version,
        $description,
        $access_level,
        $active,
        $id
    ]);

    // No replacement file?

    if (
        !isset($_FILES['download_file']) ||
        $_FILES['download_file']['error'] == UPLOAD_ERR_NO_FILE
    ) {

        header("Location: index.php");
        exit;
    }

    // Load current file details

    $stmt = $pdo->prepare("
        SELECT storage_name
        FROM downloads
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $download = $stmt->fetch();

}
// File information

$original_filename = $_FILES['download_file']['name'];

$tmp_name = $_FILES['download_file']['tmp_name'];

$file_size = $_FILES['download_file']['size'];

$mime_type = mime_content_type($tmp_name);


// Generate storage filename

$extension = strtolower(
    pathinfo($original_filename, PATHINFO_EXTENSION)
);


$storage_name = bin2hex(random_bytes(16));


if ($extension !== '') {
    $storage_name .= '.' . $extension;
}


// Upload folder

$upload_dir = __DIR__ . '/../../uploads/downloads/';


// Create folder if missing

if (!is_dir($upload_dir)) {

    mkdir(
        $upload_dir,
        0755,
        true
    );

}


// Final location

$destination = $upload_dir . $storage_name;

if ($id > 0 && !empty($download['storage_name'])) {

    $old_file =
        $upload_dir . $download['storage_name'];

    if (file_exists($old_file)) {
        unlink($old_file);
    }

}

// Move uploaded file

if (!move_uploaded_file($tmp_name, $destination)) {

    die('Unable to save uploaded file');

}



// Insert database record

if ($id == 0) {

$stmt = $pdo->prepare("

INSERT INTO downloads
(
    category_id,
    title,
    version,
    description,
    original_filename,
    storage_name,
    mime_type,
    file_size,
    access_level,
    uploaded_by,
    active
)

VALUES
(
    ?,
    ?,
    ?,
    ?,
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

    $category_id,

    $title,

    $version,

    $description,

    $original_filename,

    $storage_name,

    $mime_type,

    $file_size,

    $access_level,

    $_SESSION['user_id'],

    $active

]);
}
else {

    $stmt = $pdo->prepare("
        UPDATE downloads
        SET
            original_filename=?,
            storage_name=?,
            mime_type=?,
            file_size=?
        WHERE id=?
    ");

    $stmt->execute([
        $original_filename,
        $storage_name,
        $mime_type,
        $file_size,
        $id
    ]);

}


// Return to list

header("Location: index.php");

exit;