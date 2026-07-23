<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;

$message = "";

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_qso_fields
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch();


if (!$field) {
    die("Field not found");
}

$contest_id = $field['contest_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $stmt = $pdo->prepare("
    UPDATE contest_qso_fields
    SET
        position = ?,
        field_name = ?,
        source_field = ?,
        field_width = ?,
        alignment = ?,
        default_value = ?
    WHERE id = ?
");


$stmt->execute([
    $_POST['position'],
    strtoupper(trim($_POST['field_name'])),
    $_POST['source_field'],
    $_POST['field_width'] ?: null,
    $_POST['alignment'],
    $_POST['default_value'],
    $id
]);


        header(
    "Location: qso.php?id=" . $field['contest_id']
);

exit;


    } catch (PDOException $e) {

        $message = $e->getMessage();

    }

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
    Contest Wizard - QSO Field Edit
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
            Edit Station Field
        </h3>
    </div>


    <form method="post">


    <label>
        Position
    </label>

    <input
        type="number"
        name="position"
        value="<?= e($field['position']) ?>"
        required
    >


    <br>


    <label>
        Field Name
    </label>

    <input
        type="text"
        name="field_name"
        value="<?= e($field['field_name']) ?>"
        required
    >


    <br>


    <label>
        Source Field
    </label>

    <select name="source_field">
		<option value="station.callsign"
		<?= $field['source_field']=='station.callsign'?'selected':'' ?>>
		Station Callsign</option>

		<option value="station.operator"
		<?= $field['source_field']=='station.operator'?'selected':'' ?>>
		Station Operator</option>
						
		<option value="station.power"
		<?= $field['source_field']=='station.Power'?'selected':'' ?>>
		Station Power</option>
		
		<option value="BAND"
		<?= $field['source_field']=='BAND'?'selected':'' ?>>
		BAND
		</option>
		
		<option value="MODE"
		<?= $field['source_field']=='MODE'?'selected':'' ?>>
		MODE
		</option>
		
		<option value="QSO_DATE"
		<?= $field['source_field']=='QSO_DATE'?'selected':'' ?>>
		QSO_DATE
		</option>
		
		<option value="TIME_ON"
		<?= $field['source_field']=='TIME_ON'?'selected':'' ?>>
		TIME_ON
		</option>
		
		<option value="CALL"
		<?= $field['source_field']=='CALL'?'selected':'' ?>>
		CALL
		</option>
		
		<option value="RST_SENT"
		<?= $field['source_field']=='RST_SENT'?'selected':'' ?>>
		RST_SENT
		</option>
		
		<option value="STX"
		<?= $field['source_field']=='STX'?'selected':'' ?>>
		STX
		</option>
		
		<option value="RST_RCVD"
		<?= $field['source_field']=='RST_RCVD'?'selected':'' ?>>
		RST_RCVD
		</option>
		
		<option value="SRX"
		<?= $field['source_field']=='SRX'?'selected':'' ?>>
		SRX
		</option>

</select>

    <br>


    <label>
        Field Width
    </label>

    <input
        type="number"
        name="field_width"
        value="<?= e($field['field_width']) ?>"
    >


    <br>


    <label>
        Alignment
    </label>

    <select name="alignment">

        <option value=""
            <?= $field['alignment'] == '' ? 'selected' : '' ?>>
            Default
        </option>

        <option value="left"
            <?= $field['alignment'] === 'left' ? 'selected' : '' ?>>
            Left
        </option>

        <option value="right"
            <?= $field['alignment'] === 'right' ? 'selected' : '' ?>>
            Right
        </option>

    </select>


    <br>


    <label>
        Default Value
    </label>

    <input
        type="text"
        name="default_value"
        value="<?= e($field['default_value']) ?>"
    >


    <br><br>


    <button type="submit">
        Save Changes
    </button>


</form>

</div>
      
</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>