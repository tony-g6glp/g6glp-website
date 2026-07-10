<?php

require_once __DIR__ . '/../../include/admin.php';


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

<h2>Media Library</h2>

<p>
<a href="upload.php">
Upload Image
</a>
</p>


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


</div>


<?php endforeach; ?>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>