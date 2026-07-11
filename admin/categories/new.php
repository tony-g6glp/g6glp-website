<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_categories');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {

        $message = "Category name is required.";

    } else {

        $slug = create_slug($name);

        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug)
            VALUES (?, ?)
        ");

        $stmt->execute([
            $name,
            $slug
        ]);

        redirect('/admin/categories/index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<title>New Category</title>
<link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>
<div class="container">

<h2>New Category</h2>

<?php if ($message): ?>

<p class="message">
<?= e($message) ?>
</p>

<?php endif; ?>


<form method="post">

	<?= csrf_field(); ?>
<p>
<label for="name">
Category name
</label>
</p>

<input type="text" id="name" name="name">


<p>
<button type="submit">
Save
</button>
</p>

</form>

</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>