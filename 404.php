<?php
http_response_code(404);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>Page Not Found</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/include/public-header.php'; ?>

<?php include __DIR__ . '/include/public-nav.php'; ?>


<div class="container">

<div class="card">

<h1>404 - Page Not Found</h1>

<p>
Sorry, the page you are looking for does not exist or may have been removed.
</p>

<p>
<a href="/g6glp/blog/index.php">
Return to the blog
</a>
</p>

</div>

</div>


<?php include __DIR__ . '/include/footer.php'; ?>


</body>
</html>