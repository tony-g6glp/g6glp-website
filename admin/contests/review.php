<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');
$errors = [];
$warnings = [];

$id = $_GET['id'] ?? 0;

// Contest

$stmt = $pdo->prepare("
    SELECT *
    FROM contests
    WHERE id = ?
");

$stmt->execute([$id]);

$contest = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$contest) {
    die("Contest not found");
}


// Counts

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM contest_headers
    WHERE contest_id = ?
");

$stmt->execute([$id]);

$header_count = $stmt->fetchColumn();



$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM contest_fields
    WHERE contest_id = ?
");

$stmt->execute([$id]);

$field_count = $stmt->fetchColumn();



$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM contest_qso_fields
    WHERE contest_id = ?
");

$stmt->execute([$id]);

$qso_count = $stmt->fetchColumn();


if ($header_count == 0) {
    $errors[] = "No Cabrillo header fields have been defined.";
}
if ($field_count == 0) {
    $errors[] = "No station fields have been defined.";
}
if ($qso_count == 0) {
    $errors[] = "No QSO layout has been defined.";	
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>
Contest Wizard - Review
</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>
<title>
Contest Wizard - Review
</title>>
<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>
<div class="card">

<div class="container">
<div class="card">
<h1>
    Contest Wizard - Contest Review
</h1>
<h2>
    <?= e($contest['name']) ?>
</h2>

    <div class="card-header">
        <h3>
            Contest Definition
        </h3>
    </div>


    <table class="admin-table">

		<table class="admin-table">

<tr>
    <th>Section</th>
    <th>Status</th>
    <th>Action</th>
</tr>


<tr>
    <td>
        Headers
    </td>

    <td>
        <?= $header_count ?> defined
    </td>

    <td>
        <a href="headers.php?id=<?= $id ?>">
            Edit
        </a>
    </td>
</tr>


<tr>
    <td>
        Station Fields
    </td>

    <td>
        <?= $field_count ?> defined
    </td>

    <td>
        <a href="fields.php?id=<?= $id ?>">
            Edit
        </a>
    </td>
</tr>


<tr>
    <td>
        QSO Layout
    </td>

    <td>
        <?= $qso_count ?> fields defined
    </td>

    <td>
        <a href="qso.php?id=<?= $id ?>">
            Edit
        </a>
    </td>
</tr>


</table>
<?php	if ($errors) {

    echo "<div class='message error'>";
    echo "<h3>Contest Definition Errors</h3>";

    foreach ($errors as $error) {
        echo "<p>? " . e($error) . "</p>";
    }

    echo "</div>";

} else {

    echo "<div class='message success'>";
    echo "<h3>Contest definition is valid.</h3>";
    echo "<p>? Ready to generate Cabrillo.</p>";
    echo "</div>";

} ?>
<h3>
    Header Fields
</h3>

<table class="admin-table">

<tr>
    <th>Position</th>
    <th>Cabrillo Field</th>
    <th>Source</th>
</tr>


<?php

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_headers
    WHERE contest_id = ?
    ORDER BY sort_order
");

$stmt->execute([$id]);

foreach ($stmt as $header):

?>

<tr>

<td>
<?= e($header['sort_order']) ?>
</td>

<td>
<?= e($header['header_name']) ?>
</td>

<td>
<?= e($header['source_field']) ?>
</td>

</tr>


<?php endforeach; ?>


</table>
<h3>
    Station Fields
</h3>
<h3>
    Header Fields
</h3>

<table class="admin-table">

<tr>
    <th>Order</th>
    <th>Label</th>
    <th>Field</th>
</tr>


<?php

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_fields
    WHERE contest_id = ?
    ORDER BY sort_order
");

$stmt->execute([$id]);

foreach ($stmt as $header):

?>

<tr>

<td>
<?= e($header['sort_order']) ?>
</td>

<td>
<?= e($header['label']) ?>
</td>

<td>
<?= e($header['field_name']) ?>
</td>

</tr>


<?php endforeach; ?>


</table>
<h3>
    QSO Layout
</h3>
<h3>
    Header Fields
</h3>

<table class="admin-table">

<tr>
    <th>Position</th>
    <th>Field Name</th>
    <th>Source Field</th>
	<th>Width</th>
	<th>Alignment</th>
</tr>


<?php

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_qso_fields
    WHERE contest_id = ?
    ORDER BY position
");

$stmt->execute([$id]);

foreach ($stmt as $header):

?>

<tr>

<td>
<?= e($header['position']) ?>
</td>

<td>
<?= e($header['field_name']) ?>
</td>

<td>
<?= e($header['source_field']) ?>
</td>

<td>
<?= e($header['field_width']) ?>
</td>

<td>
<?= e($header['alignment']) ?>
</td>
</tr>


<?php endforeach; ?>


</table>
<?php
if (empty($errors)) {
?>
<div class="card">
    <div class="card-body">

        <a href="generate.php?id=<?= $id ?>">
            Complete Contest Setup
        </a><br><br>
<a href="index.php">
    Return to Contest Manager
</a><br><br>
<a href="activate.php?id=<?= $id ?>">
    Activate Contest
</a>
    </div>
</div>
 <?php } ?>
</div>

</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>
