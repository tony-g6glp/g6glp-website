<?php

require_once __DIR__ . '/../../include/bootstrap.php';

verify_csrf();

/*
if (!can('use_adif_converter')) {

    http_response_code(403);
    die('Access denied');

} */


if (!isset($_FILES['adif'])) {

    die('No file uploaded');

}


$file = $_FILES['adif'];

if ($file['error'] !== UPLOAD_ERR_OK) {

    die('Upload failed');

}


$extension = strtolower(
    pathinfo($file['name'], PATHINFO_EXTENSION)
);


if (!in_array($extension, ['adi', 'adif'])) {

    die('Invalid file type');

}


/*
    Create storage directory if required
*/

$upload_dir = __DIR__ . '/uploads/';

if (!is_dir($upload_dir)) {

    mkdir($upload_dir, 0755, true);

}


/*
    Create unique filename
*/

$token = bin2hex(random_bytes(16));

$filename = $token . '.adi';

$destination = $upload_dir . $filename;

$created = date('Y-m-d H:i:s');

$expires = date(
    'Y-m-d H:i:s',
    strtotime('+14 days')
);


$stmt = $pdo->prepare("
    INSERT INTO adif_jobs
    (
        token,
        original_filename,
        stored_filename,
        created_at,
        expires_at
    )
    VALUES (?, ?, ?, ?, ?)
");


$stmt->execute([
    $token,
    $file['name'],
    $filename,
    $created,
    $expires
]);

if (!move_uploaded_file(
    $file['tmp_name'],
    $destination
)) {

    die('Unable to save uploaded file');

}


?>
<!DOCTYPE html>
<html>
<head>
    <title>ADIF Upload Complete</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/public-nav.php'; ?>

<div class="container">

<h1>Upload Complete</h1>

<p>
Your ADIF file has been uploaded successfully.
</p>

<p>
Reference:
<strong>
<a href="result.php?token=<?= urlencode($token) ?>">
<?= htmlspecialchars($token) ?>
</a>
</strong>
</p>

<p>
File:
<?= htmlspecialchars($file['name']) ?>
</p>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>