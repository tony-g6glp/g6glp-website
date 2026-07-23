
<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$pdo->beginTransaction();

try {
    $contest_id = trim($_POST['contest_id']);
    $name = trim($_POST['name']);
    $cabrillo_name = trim($_POST['cabrillo_name']);
    $cabrillo_version = trim($_POST['cabrillo_version']);
    $description = trim($_POST['description']);
    $active = isset($_POST['active']) ? 1 : 0;


    try {

        $stmt = $pdo->prepare("
            INSERT INTO contests
            (
                contest_id,
                name,
                cabrillo_name,
                cabrillo_version,
                description,
                active
            )
            VALUES
            (?, ?, ?, ?, ?, ?)
        ");


        $stmt->execute([
            $contest_id,
            $name,
            $cabrillo_name,
            $cabrillo_version,
            $description,
            $active
        ]);


        $id = $pdo->lastInsertId();
		


// Add mandatory header field

$stmt = $pdo->prepare("
    INSERT INTO contest_headers
    (
        contest_id,
        header_name,
        source_field,
        sort_order
    )
    VALUES
    (?, ?, ?, ?)
");

$stmt->execute([
    $new_contest_id,
    'CALL',
    'callsign',
    1
]);

$new_header_id = $pdo->lastInsertId();

// Add mandatory Station field

$stmt = $pdo->prepare("
    INSERT INTO contest_fields
    (
        contest_id,
        field_name,
        label,
		field_type,
		required,
		sort_order
    )
    VALUES
    (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $new_contest_id,
    'callsign',
	'CALLSIGN',    
	'text',
	1,
	1
]);

$new_Field_id = $pdo->lastInsertId();

$pdo->commit();

	} catch (Exception $e) {

    $pdo->rollBack();
    throw $e;

}
        header(
            "Location: headers.php?id=" . $id
        );




    } catch (PDOException $e) {

        $message = "Contest ID already exists";

    }

}

?>

<h1>New Contest</h1>

<?php if ($message): ?>

<p>
    <?= e($message) ?>
</p>

<?php endif; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>

<div class="container">

<form method="post">


<label>
Contest ID
</label>
<input name="contest_id" required>


<label>
Name
</label>
<input name="name" required>


<label>
Cabrillo Name
</label>
<input name="cabrillo_name" required>


<label>
Cabrillo Version
</label>
<input 
    name="cabrillo_version"
    value="3.0"
>


<label>
Description
</label>
<textarea name="description"></textarea>


<label>
<input 
    type="checkbox"
    name="active"
    checked
>
Active
</label>


<button type="submit">
Create Contest
</button>


</form>
</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>