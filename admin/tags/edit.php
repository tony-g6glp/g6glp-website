<?php
require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_tags');

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/admin/tags/index.php');
}

// Load tag

$stmt = $pdo->prepare("
    SELECT id, name, slug
    FROM tags
    WHERE id = ?
");

$stmt->execute([$id]);

$tag = $stmt->fetch();

if (!$tag) {
    redirect('/admin/tags/index.php');
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
verify_csrf();
	
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {

        $message = "Tag name is required.";
    } else {
        $slug = create_slug($name);

        $stmt = $pdo->prepare("
            UPDATE tags
            SET name = ?, slug = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $name,
            $slug,
            $id
        ]);
        redirect('/admin/tags/index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Tag</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h2>Edit Tag</h2>

<?php if ($message): ?>

<p>
<?= e($message) ?>
</p>

<?php endif; ?>


<form method="post">
	<?= csrf_field(); ?>
<p>
<label for="name">
Tag name
</label>
</p>

<input
type="text"
name="name"
id="name"
value="<?= e($tag['name']) ?>"
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