<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');

$id = $_GET['id'] ?? 0;

$message = "";


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
    die("Contest not found");
}


/*
    Add station field
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $field_name = strtolower(trim($_POST['field_name']));


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
            $id,
            $field_name,
            $_POST['label'],
            $_POST['field_type'],
            isset($_POST['required']) ? 1 : 0,
            $_POST['sort_order']
        ]);


        header("Location: fields.php?id=" . $id);
        exit;


    } catch (PDOException $e) {

        $message = $e->getMessage();

    }

}


/*
    Load station fields
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_fields
    WHERE contest_id = ?
    ORDER BY sort_order
");

$stmt->execute([$id]);

$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<div class="card">

<div class="container">

<h1>
    Contest Wizard - 
</h1>
<div class="help-box">

<h3>What are Station Fields?</h3>

<p>
Station fields are the details entered once by the operator before
creating the Cabrillo file.
</p>

<p>
Examples:
Callsigh, Operator, Power, Locator
</p>

<p>
These are NOT QSO fields.
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
            Current Station Fields
        </h3>
    </div>


    <table class="admin-table">

        <tr>
            <th>Order</th>
            <th>Label</th>
            <th>Field Name</th>
            <th>Type</th>
            <th>Required</th>
			<th>Actions</th>
        </tr>


        <?php foreach ($fields as $field): ?>

        <tr>

            <td>
                <?= e($field['sort_order']) ?>
            </td>

            <td>
                <?= e($field['label']) ?>
            </td>

            <td>
                <?= e($field['field_name']) ?>
            </td>

            <td>
                <?= e($field['field_type']) ?>
            </td>

            <td>
                <?= $field['required'] ? 'Yes' : 'No' ?>
            </td>
			<td>
				<?php if ($field['field_name'] === 'callsign'): ?>

    		Protected

			<?php else: ?>
    			<a href="fields_edit.php?id=<?= $field['id'] ?>">
        		Edit</a>				
			    |
				<a href="fields_delete.php?id=<?= $field['id'] ?>"
				onclick="return confirm('Delete this field?');">
				Delete
				</a>
			<?php endif; ?>
			</td>

        </tr>
<?php endforeach; ?>



<div class="card">

    <div class="card-header">
        <h3>
            Add New Field
        </h3>
    </div>


    <form method="post">


        <table class="admin-table">

            <tr>
                <th>Label</th>
                <th>Field Name</th>
                <th>Type</th>
                <th>Required</th>
                <th>Order</th>
            </tr>


            <tr>

                <td>
                    <input
                        type="text"
                        name="label"
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
                    <select name="field_type">

                        <option value="text">
                            Text
                        </option>

                        <option value="select">
                            Select
                        </option>

                        <option value="checkbox">
                            Checkbox
                        </option>

                    </select>
                </td>


                <td>
                    <input
                        type="checkbox"
                        name="required"
                        value="1"
                    >
                </td>


                <td>
                    <input
                        type="number"
                        name="sort_order"
                        required
                    >
                </td>

            </tr>


        </table>


        <br>


        <button type="submit">
            Add Field
        </button>


    


    <div class="card-body">
			<br>
        <a href="qso.php?id=<?= $id ?>">
            <button type="button">
                Continue to QSO Fields
            </button>
        </a>

   
</form>
</div>
</table></div>
       
</div> 


</div>
<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>