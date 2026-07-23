<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$position = (int)$_POST['position'];

		$stmt = $pdo->prepare("
			SELECT COUNT(*)
			FROM contest_qso_fields
			WHERE contest_id = ?
			AND position = ?
		");
		
		$stmt->execute([
			$id,
			$position
		]);
		
		if ($stmt->fetchColumn()) {
		
			$message = "Position already exists";
		
		} else {
		
			// INSERT HERE
		
		}
		
    try {

        $stmt = $pdo->prepare("
            INSERT INTO contest_qso_fields
            (
                contest_id,
                position,
                field_name,
                source_field,
                field_width,
                alignment,
                default_value
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?)
        ");


        $stmt->execute([
            $id,
            $_POST['position'],
            strtoupper(trim($_POST['field_name'])),
            $_POST['source_field'],
            $_POST['field_width'] ?: null,
            $_POST['alignment'],
            $_POST['default_value']
        ]);


        $message = "QSO field added successfully";


    } catch (PDOException $e) {

        $message = $e->getMessage();

    }

}

$stmt = $pdo->prepare("
    SELECT *
    FROM contests
    WHERE id = ?
");

$stmt->execute([$id]);

$contest = $stmt->fetch();


if (!$contest) {
    die("Contest not found");
}


$stmt = $pdo->prepare("
    SELECT *
    FROM contest_qso_fields
    WHERE contest_id = ?
    ORDER BY position
");

$stmt->execute([$id]);

$qso_fields = $stmt->fetchAll();


// temporary test


?>
<!DOCTYPE html>
<html>
<head>
   <title>
	Contest Wizard - QSO Layout
	</title>
    <link rel="stylesheet" href="/g6glp/include/css.css">
</head>
<title>
	Contest Wizard - QSO Layout
	</title>
<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/nav.php'; ?>
<div class="card">

<div class="container">
<div class="card">
<h1>
    Contest Wizard - QSO Layout
</h1>
<div class="help-box">

<h3>What is QSO Layout?</h3>

<p>
QSO layout defines how each contact is written into the Cabrillo log.
</p>

<p>
Each QSO creates one output line.
</p>

<p>
Examples:
BAND, MODE, DATE, TIME, CALL, RST, SERIAL NUMBERS
</p>

</div>
<h2>
    <?= e($contest['name']) ?>
</h2>
<?php if ($message): ?>

<div class="message">
    <?= e($message) ?>
</div>

<?php endif; ?>

    <div class="card-header">
        <h3>
            Current QSO Fields
        </h3>
    </div>


    <table class="admin-table">

        <tr>
            <th>Position</th>
            <th>Field Name</th>
            <th>Source Field</th>
            <th>Width</th>
            <th>Alignment</th>
            <th>Default</th>
			<th>Actions</th>
        </tr>


        <?php foreach ($qso_fields as $field): ?>

        <tr>

            <td>
                <?= e($field['position']) ?>
            </td>

            <td>
                <?= e($field['field_name']) ?>
            </td>

            <td>
                <?= e($field['source_field']) ?>
            </td>

            <td>
                <?= e($field['field_width']) ?>
            </td>

            <td>
                <?= e($field['alignment']) ?>
            </td>

            <td>
                <?= e($field['default_value']) ?>
            </td>
			<td>

				<a href="qso_edit.php?id=<?= $field['id'] ?>">
					Edit
				</a>
			
				|
			
				<a
					href="qso_delete.php?id=<?= $field['id'] ?>"
					onclick="return confirm('Delete this QSO field?');"
				>
					Delete
				</a>
			</td>
        </tr>

        <?php endforeach; ?>


    </table>

</div>
<div class="card">

    <div class="card-header">
        <h3>
            Add QSO Field
        </h3>
    </div>


    <form method="post">


        <table class="admin-table">

            <tr>
                <th>Position</th>
                <th>Field Name</th>
                <th>Source Field</th>
                <th>Width</th>
                <th>Alignment</th>
                <th>Default</th>
            </tr>


            <tr>

                <td>
                    <input
                        type="number"
                        name="position"
                        required
                    >
                </td>


                <td>
                    <input
                        type="text"
                        name="field_name"
                        required
                    >
                </td>


                <td>
                    <select name="source_field" required>
						<option value="station.callsign">Station Callsign</option>
						<option value="station.operator">Station Operator</option>
						<option value="station.power">Station Power</option>
						
						<option value="">Select ADIF Field</option>
					
						<option value="BAND">BAND</option>
						<option value="MODE">MODE</option>
						<option value="QSO_DATE">QSO_DATE</option>
						<option value="TIME_ON">TIME_ON</option>
						<option value="CALL">CALL</option>
						<option value="RST_SENT">RST_SENT</option>
						<option value="STX">STX</option>
						<option value="RST_RCVD">RST_RCVD</option>
						<option value="SRX">SRX</option>
					
					</select>
                </td>


                <td>
                    <input
                        type="number"
                        name="field_width"
                    >
                </td>


                <td>
                    <select name="alignment">

                        <option value="">
                            Default
                        </option>

                        <option value="left">
                            Left
                        </option>

                        <option value="right">
                            Right
                        </option>

                    </select>
                </td>


                <td>
                    <input
                        type="text"
                        name="default_value"
                    >
                </td>


            </tr>


        </table>


        <br>


        <button type="submit">
            Add QSO Field
        </button>
	
	</form><br>
<a href="review.php?id=<?= $id ?>">
    Continue to Review ?
</a>

    

</div>
</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>
