<?php
require_once __DIR__ . '/../include/bootstrap.php';
?>

<!DOCTYPE html>
<html>

<head>
<title>Content Coming Soon</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

<style>
.construction-box {
    max-width: 700px;
    margin: 60px auto;
    text-align: center;
    padding: 40px;
    border-radius: 10px;
    background: #f5f5f5;
	font-color: #222;
}

.construction-icon {
    font-size: 60px;
    margin-bottom: 20px;
}

.construction-box h1 {
    margin-bottom: 20px;
}

.construction-box p {
    font-size: 18px;
    line-height: 1.6;
}

.back-button {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 20px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

</style>

</head>

<body>

<?php include __DIR__ . '/../include/public-header.php'; ?>

<?php include __DIR__ . '/../include/public-nav.php'; ?>


<div class="container">

<div class="construction-box">

<div class="construction-icon">
?? Boo
</div>

<h1>Content Coming Soon</h1>

<p>
This section is currently under development.
</p>

<p>
We are preparing new content and information for this area.
Please check back soon.
</p>

<a class="back-button" href="/g6glp/public/index.php">
Return to Blog
</a>

</div>

</div>


<?php include __DIR__ . '/../include/footer.php'; ?>

</body>

</html>