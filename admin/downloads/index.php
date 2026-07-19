<?php

require_once __DIR__ . '/../../include/admin.php';


if (
    !can('manage_downloads') &&
    !can('edit_downloads') &&
    !can('edit_own_downloads')
) {
    http_response_code(403);
    die('Access denied');
}

$stmt = $pdo->query("
    SELECT
    d.*,
    dc.name AS category_name,
    u.username
FROM downloads d

LEFT JOIN download_categories dc
    ON d.category_id = dc.id

LEFT JOIN admin_users u
    ON d.uploaded_by = u.id

ORDER BY d.uploaded_at DESC
");

$downloads = $stmt->fetchAll();

if (can('manage_downloads') || can('edit_downloads')) {
    
    // existing query

}
else {

    $stmt = $pdo->prepare("
        SELECT
            d.*,
            dc.name AS category_name
        FROM downloads d

        LEFT JOIN download_categories dc
            ON d.category_id = dc.id

        WHERE d.uploaded_by = ?

        ORDER BY d.uploaded_at DESC
    ");

    $stmt->execute([
        $_SESSION['user_id']
    ]);

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Downloads</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<h2>Downloads</h2>

<p>
<a href="new.php">Upload New File</a>
</p>

<?php if (!$downloads): ?>

<p>No downloads uploaded.</p>

<?php else: ?>

<table>

<tr>

<th>Title</th>

<th>Category</th>

<th>Version</th>

<th>Access</th>

<th>Size</th>

<th>Uploaded By</th>

<th>Date</th>

<th>Active</th>

<th>Actions</th>

</tr>

<?php foreach ($downloads as $download): ?>

<tr>

<td>

<?= e($download['title']) ?>

</td>

<td>

<?= e($download['category_name']) ?>

</td>

<td>

<?= e($download['version']) ?>

</td>

<td>

<?= ucfirst(e($download['access_level'])) ?>

</td>

<td>

<?= number_format($download['file_size'] / 1024,1) ?> KB

</td>

<td>

<?= e($download['username']) ?>

</td>

<td>

<?= e($download['uploaded_at']) ?>

</td>

<td>

<?= $download['active'] ? 'Yes' : 'No' ?>

</td>

<td>
<a href="download.php?id=<?= $download['id'] ?>">
Download
</a>
|
<a href="edit.php?id=<?= $download['id'] ?>">

Edit

</a>

|

<form
method="post"
action="delete.php"
style="display:inline;">

<?= csrf_field(); ?>

<input
type="hidden"
name="id"
value="<?= $download['id'] ?>">

<button
class="link-button"
type="submit"
onclick="return confirm('Delete this download?');">

Delete

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>

</html>