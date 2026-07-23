<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;

if (!$id) {
    die('Missing contest id');
}


/*
    Load contest
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM contests
    WHERE id = ?
");

$stmt->execute([$id]);

$contest = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$contest) {
    die('Contest not found');
}


/*
    Add header
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $position = (int) $_POST['position'];
    $cabrillo_field = strtoupper(trim($_POST['cabrillo_field']));
    $source_field = trim($_POST['source_field']);


    if ($cabrillo_field && $source_field) {
		
		$stmt = $pdo->prepare("
			SELECT COUNT(*)
			FROM contest_headers
			WHERE contest_id = ?
			AND header_name = ?
			");
			
			$stmt->execute([
				$id,
				$cabrillo_field
			]);
			
			if ($stmt->fetchColumn()) {
				die("Header already exists");
		}

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
		$id,
		$cabrillo_field,
		$source_field,
		$position
	]);


        header(
            "Location: headers.php?id=" . $id
        );

        exit;

    }

}


/*
    Existing headers
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_headers
    WHERE contest_id = ?
    ORDER BY sort_order
");

$stmt->execute([$id]);

$headers = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
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
<div class="card">

    <div class="card-header">
        <h1>
            Contest Wizard - Content Headers 
        </h1>
<div class="help-box">

<h3>What are Header Fields?</h3>

<p>
Header fields define the Cabrillo information written once at the
start of the log file.
</p>

<p>
Examples:
CALLSIGN, NAME, CATEGORY-POWER
</p>

</div>
        <h2>
            <?= e($contest['name']) ?>
        </h2>
    </div>


    <div class="card-body">




        <h3>
            Current Header Fields
        </h3>

	<table border="1">
	
	<tr>
		<th>Position</th>
		<th>Cabrillo Field</th>
		<th>Source Field</th>
		<th>Actions</th>
	</tr>
			

        

        <?php foreach ($headers as $header): ?>

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

        
		<td>
		<?php if ($header['header_name'] === 'CALL'): ?>

    		Protected

		<?php else: ?>

		<a href="header_edit.php?id=<?= $header['id'] ?>">
			Edit
		</a>
		
		<a href="header_delete.php?id=<?= $header['id'] ?>"
		   onclick="return confirm('Delete this header?');">
			Delete
		</a>
		<?php endif; ?>
		
		</td>
	</tr>
        <?php endforeach; ?>


        </table>


    </div>

</div>
<div class="container">

<div class="card">

    <div class="cards">
        <h3>
            Add New Header Field
        </h3>
    </div>


    <div class="card-body">


        <form method="post">


        <label>
        Position
        </label>

        <input 
            type="number"
            name="position"
            required
        >


        <label>
        Cabrillo Field
        </label>

        <input 
            type="text"
            name="cabrillo_field"
            required
        >


        <label>
        Source Field
        </label>

        <select name="source_field">

			<option value="operator">Operator</option>
			<option value="power">Power</option>
			<option value="category">Category</option>
			<option value="club">Club</option>

		</select>


        <button type="submit">
            Add Header
        </button>


        </form>


    </div>

</div>
<br>

<div class="card">

    <div class="card-body">

        <a href="fields.php?id=<?= $id ?>">
            Continue to Station Fields ?
        </a>

    </div>

</div>

</div>
<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>