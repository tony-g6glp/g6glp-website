<?php

require_once __DIR__ . '/../../include/bootstrap.php';
/*
if (!can('use_adif_converter')) {

    http_response_code(403);
    die('Access denied');

}
*/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/public-nav.php'; ?>

<div class="container">

<div class="card">

<h1>ADIF to Cabrillo Converter</h1>

<p>
Upload your ADIF log file to begin.
</p>

<form method="post" action="upload.php" enctype="multipart/form-data">

    <input 
        type="file" 
        name="adif" 
        accept=".adi,.adif"
        required
    >
	<?= csrf_field() ?>
    <br><br>

    <button type="submit">
        Upload ADIF
    </button>

</form>
</div>
</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>
</body>
</html>