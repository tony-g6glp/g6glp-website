<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('create_downloads');

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

<?php include 'download_form.php'; ?>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>