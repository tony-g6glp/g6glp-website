<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_categories');

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/admin/categories/list.php');
}

// Load category

$stmt = $pdo->prepare("
    SELECT id, name, slug
	FROM categories
    WHERE id = ?
");

$stmt->execute([$id]);

$category = $stmt->fetch();

if (!$category) {
    redirect('/admin/categories/list.php');
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {

        $message = "Category name is required.";

    } else {

        $slug = create_slug($name);

        $stmt = $pdo->prepare("
            UPDATE categories
            SET name = ?, slug = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $name,
            $slug,
            $id
        ]);

        redirect('/admin/categories/index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Category</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h2>Edit Category</h2>

<?php if ($message): ?>

<p>
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

<input 
type="text"
name="name"
id="name"
value="<?= e($category['name']) ?>"
>

<p>

<button type="submit">
Save Changes
</button>

</p>

</form>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>