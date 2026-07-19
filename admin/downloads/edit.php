<?php

require_once __DIR__ . '/../../include/admin.php';

$id = (int)($_GET['id'] ?? 0);

if ($id < 1) {
    die('Invalid download ID');
}


if (!can('edit_downloads')) {

    $stmt = $pdo->prepare("
        SELECT uploaded_by
        FROM downloads
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $owner = $stmt->fetchColumn();


    if (
        !can('edit_own_downloads') ||
        $owner != $_SESSION['user_id']
    ) {

        http_response_code(403);
        die('Access denied');

    }

}

$id = (int)($_GET['id'] ?? 0);

if ($id < 1) {
    die('Invalid download ID');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM downloads
    WHERE id = ?
");

$stmt->execute([$id]);
;
$download = $stmt->fetch();

if (!$download) {
    die("Download not found");
}
// Read categories
$stmt = $pdo->query("
    SELECT id, name
    FROM download_categories
    WHERE active = 1
    ORDER BY sort_order, name
");

$categories = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

<title>Upload Download</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<?php include 'download_form.php';
?>
<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>