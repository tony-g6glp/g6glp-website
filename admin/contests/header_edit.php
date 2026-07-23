<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("
    SELECT *
    FROM contest_headers
    WHERE id = ?
");

$stmt->execute([$id]);

$header = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $stmt = $pdo->prepare("
   UPDATE contest_headers
SET
    header_name = ?,
    source_field = ?,
    sort_order = ?
WHERE id = ?
");


$stmt->execute([
    $_POST['header_name'],
    $_POST['source_field'],
    $_POST['sort_order'],
    $id
]);


        $message = "Field updated successfully";


    } catch (PDOException $e) {

        $message = $e->getMessage();

    }
	header(
    "Location: headers.php?id=" . $header['contest_id']
);

exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_headers
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch();


if (!$field) {
    die("Field not found");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM contests
    WHERE id = ?
");

$stmt->execute([$field['contest_id']]);

$contest = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contest) {
    die("Contest not found");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>
<title>
Edit Station Field
</title>
<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>
<div class="card">

<div class="container">

<h1>
    Contest Wizard - Header Field Edit
</h1>
<h2>
    <?= e($contest['name']) ?>
</h2>
<link rel="stylesheet" href="/g6glp/include/css.css">

</head>


<body>




<div class="container">

<?php if ($message): ?>

<div class="message">
    <?= e($message) ?>
</div>

<?php endif; ?>

<div class="card">

    <div class="card-header">
        <h3>
            Edit Header Field
        </h3>
    </div>


    <form method="post">


    <label>
        Position
    </label>

    <input
        type="number"
        name="position"
        value="<?= e($field['sort_order']) ?>"
        required
    >


    <br>


    <label>
        Source Name
    </label>

    <input
        type="text"
        name="header_name"
        value="<?= e($field['header_name']) ?>"
        required
    >


    <br>


    <label>
        Source Field
    </label>

    <input
        type="text"
        name="source_field"
        value="<?= e($field['source_field']) ?>"
        required
    >
	<br>
	<label>
        Sort Order
    </label>

    <input
        type="number"
        name="sort_order"
        value="<?= e($field['sort_order']) ?>"
        required
    >
    <br>


    <label></label>
    <br>


    <button type="submit">
        Save Changes
    </button>


</form>

</div>
      
</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>