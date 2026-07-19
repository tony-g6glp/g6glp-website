<?php

require_once __DIR__ . '/include/bootstrap.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {

    $error_code = 404;
    $error_title = 'Page Not Found';
    $error_message = 'The requested page could not be found.';

    include __DIR__ . '/include/error_page.php';
    exit;
}





$stmt = $pdo->prepare("
    SELECT *
    FROM pages
    WHERE slug = ?
    AND published = 1
");

$stmt->execute([$slug]);

$page = $stmt->fetch();


if (!$page) {

    $error_code = 404;
    $error_title = 'Page Not Found';
    $error_message = 'The requested page could not be found.';

    include __DIR__ . '/include/error_page.php';
    exit;
}

// Check page access

if ($page['access_level'] === 'registered') {

    if (empty($_SESSION['logged_in'])) {

        $error_code = 403;
        $error_title = 'Login Required';
        $error_message = 'This page is available only to registered users. Please log in to continue.';

        include __DIR__ . '/include/error_page.php';
        exit;
    }

}

if ($page['access_level'] === 'admin') {

    if (
        empty($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {

        $error_code = 403;
        $error_title = 'Access Denied';
        $error_message = 'This page is available only to administrators.';

        include __DIR__ . '/include/error_page.php';
        exit;
    }

}


?>
<!DOCTYPE html>
<html>

<head>

<title><?= e($page['title']) ?></title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/include/public-header.php'; ?>

<?php include __DIR__ . '/include/public-nav.php'; ?>


<div class="container">

<h1>
<?= e($page['title']) ?>
</h1>

<div class="page-content">

<?= $page['content'] ?>
<?php
/* ******************************************
// To remove <br> and all html markup
// <?= nl2br(e($page['content'])) ?>
// To remove all Hhtml markup only 
// <?= nl2br($page['content']) ?>
// To reove <br> only 
// <?= e($page['content']) ?>
   ***************************************** */
?>
</div>

</div>


<?php include __DIR__ . '/include/footer.php'; ?>

</body>

</html>