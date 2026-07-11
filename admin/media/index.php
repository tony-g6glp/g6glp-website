<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_media');


$stmt = $pdo->query("
    SELECT 
        media.*,
        admin_users.username
    FROM media
    LEFT JOIN admin_users
        ON media.uploaded_by = admin_users.id
    ORDER BY media.created_at DESC
");

$media = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>

<title>Media Library</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">
<?php if (!empty($_SESSION['message'])): ?>

<p class="error">
<?= e($_SESSION['message']) ?>
</p>

<?php unset($_SESSION['message']); ?>

<?php endif; ?>
<h2>Media Library</h2>

<div class="card">

<a href="index.php">
Media Library
</a>

|

<a href="upload.php">
Upload Image
</a>

</div>

<br>


<div class="cards">


<?php foreach ($media as $item): ?>


<div class="card">


<img 
src="/g6glp/uploads/posts/<?= e($item['filename']) ?>"
style="max-width:200px;"
>


<h3>
<?= e($item['original_name']) ?>
</h3>


<p>
Uploaded by:
<?= e($item['username'] ?? 'Unknown') ?>
</p>


<p>
Date:
<?= e($item['created_at']) ?>
</p>


<p>
Size:
<?= e(number_format($item['file_size'] / 1024, 1)) ?> KB
</p>

<form method="post" action="delete.php">

<?= csrf_field(); ?>

<input
    type="hidden"
    name="id"
    value="<?= e($item['id']) ?>"
>

<button
    type="submit"
    onclick="return confirm('Delete this image?')">
    Delete
</button>

</form>
</div>


<?php endforeach; ?>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>