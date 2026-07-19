<?php

require_once __DIR__ . '/../include/bootstrap.php';


// Categories for filter dropdown

$stmt = $pdo->query("
    SELECT id, name
    FROM download_categories
    WHERE active = 1
    ORDER BY sort_order, name
");

$categories = $stmt->fetchAll();


// Search/filter values

$search = trim($_GET['search'] ?? '');

$category_filter = (int)($_GET['category'] ?? 0);


// Access status

$logged_in = !empty($_SESSION['logged_in']);

$is_admin = (
    $logged_in &&
    $_SESSION['role'] === 'admin'
);


// Base query

$sql = "
SELECT
    d.id,
    d.title,
    d.version,
    d.description,
    d.original_filename,
    d.file_size,
    d.access_level,
    dc.name AS category_name

FROM downloads d

LEFT JOIN download_categories dc
    ON d.category_id = dc.id

WHERE d.active = 1

AND
(
    d.access_level = 'public'

    OR

    (
        d.access_level = 'registered'
        AND :logged_in = 1
    )

    OR

    (
        d.access_level = 'admin'
        AND :is_admin = 1
    )
)
";


$params = [
    ':logged_in' => $logged_in ? 1 : 0,
    ':is_admin' => $is_admin ? 1 : 0
];


// Search filter

if ($search !== '') {

    $sql .= "
    AND
    (
        d.title LIKE :search
        OR
        d.description LIKE :search
        OR
        d.version LIKE :search
    )
    ";

    $params[':search'] = '%' . $search . '%';
}


// Category filter

if ($category_filter > 0) {

    $sql .= "
    AND d.category_id = :category
    ";

    $params[':category'] = $category_filter;
}


// Ordering

$sql .= "
ORDER BY
    dc.name,
    d.title
";


$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$downloads = $stmt->fetchAll();




$search = trim($_GET['search'] ?? '');

$category_filter = (int)($_GET['category'] ?? 0);

?>

<!DOCTYPE html>
<html>

<head>

<title>Downloads</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>


<body>

<?php include __DIR__ . '/../include/public-header.php'; ?>

<?php include __DIR__ . '/../include/public-nav.php'; ?>


<div class="container">

<h1>Downloads</h1>
<form method="get">

<input
type="text"
name="search"
placeholder="Search downloads..."
value="<?= e($search) ?>">


<select name="category">

<option value="0">
All Categories
</option>


<?php foreach ($categories as $category): ?>

<option
value="<?= $category['id'] ?>"

<?= $category_filter == $category['id']
        ? 'selected'
        : '' ?>
>

<?= e($category['name']) ?>

</option>

<?php endforeach; ?>

</select>


<button type="submit">
Search
</button>

</form>
<p>
Files and documents available for download.
</p>


<?php if (!$downloads): ?>

<p>
No downloads are currently available.
</p>


<?php else: ?>


<div class="download-list">
<?php
$current_category = '';

foreach ($downloads as $download):

?>

<?php if ($current_category !== $download['category_name']): ?>

<?php if ($current_category !== ''): ?>
</div>
<?php endif; ?>

<?php
$current_category = $download['category_name'];
?>
<h2 class="download-category">
<?= e($current_category) ?>
</h2>
<div class="download-list">

<?php endif; ?>


<div class="download-item">

<h3>
<?= e($download['title']) ?>
</h3>

<?php if (!empty($download['version'])): ?>

<p>
Version:
<?= e($download['version']) ?>
</p>

<?php endif; ?>

<p>
<?= e($download['description']) ?>
</p>

<a class="download-button"
href="/g6glp/downloads/download.php?id=<?= $download['id'] ?>">
Download
</a>

</div>


<?php endforeach; ?>


<?php if ($current_category !== ''): ?>
</div>
<?php endif; ?>
</div>

<?php endif; ?>


</div>


<?php include __DIR__ . '/../include/footer.php'; ?>

</body>

</html>