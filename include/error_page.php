<?php
http_response_code(404);

http_response_code($error_code ?? 403);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>Page Not Found</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/public-header.php'; ?>

/public-nav.php'; ?>


<div class="container">

<div class="card">


<h1><?= e($error_title) ?></h1>

<p><?= e($error_message) ?></p>

<p>
<a href="/g6glp/">Return to Home Page</a>
</p>
<?php if (($error_code ?? 403) == 403): ?>
    <p>
        <a class="button" href="/g6glp/admin/login.php?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
            Log In
        </a>
    </p>
<?php endif; ?>
</div>

</div>


<?php include __DIR__ . '/footer.php'; ?>


</body>
</html>