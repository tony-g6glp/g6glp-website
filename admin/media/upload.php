<?php

require_once __DIR__ . '/../../include/admin.php';

$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $image = upload_image(
        $_FILES['image'] ?? null,
        'posts',
        $pdo
    );


    if ($image) {

        $message = "Image uploaded successfully.";

    } else {

        $message = "Image upload failed.";

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Upload Image</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>


<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<div class="card">


<h2>Upload Image</h2>


<?php if ($message): ?>

<p>
<?= e($message) ?>
</p>

<?php endif; ?>


<form method="post" enctype="multipart/form-data">


<?= csrf_field(); ?>


<p>
Select image
</p>


<input
    type="file"
    name="image"
    accept="image/*"
    required
>


<br><br>


<button type="submit">
Upload
</button>


</form>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>