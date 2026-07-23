<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');


$id = $_GET['id'] ?? 0;

$message = "";

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_fields
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$field) {
    die("Field not found");
}


$contest_id = $field['contest_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $new_name = strtolower(trim($_POST['field_name']));


        // Check duplicate name excluding current record

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM contest_fields
            WHERE contest_id = ?
            AND field_name = ?
            AND id != ?
        ");

        $stmt->execute([
            $field['contest_id'],
            $new_name,
            $id
        ]);


        if ($stmt->fetchColumn()) {

            $message = "Field name already exists";

        } else {


            $stmt = $pdo->prepare("
                UPDATE contest_fields
                SET
                    field_name = ?,
                    label = ?,
                    field_type = ?,
                    required = ?,
                    sort_order = ?
                WHERE id = ?
            ");


            $stmt->execute([
                $new_name,
                $_POST['label'],
                $_POST['field_type'],
                isset($_POST['required']) ? 1 : 0,
                $_POST['sort_order'],
                $id
            ]);


            header(
                "Location: fields.php?id=" . $field['contest_id']
            );

            exit;

        }


    } catch (PDOException $e) {

        $message = $e->getMessage();

    }

}

$stmt = $pdo->prepare("
    SELECT *
    FROM contest_fields
    WHERE id = ?
");

$stmt->execute([$id]);

$field = $stmt->fetch();

if (!$field) {
    die("Field not found");
}

$contest_id = $field['contest_id'];
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
    Contest Wizard - Station Field 
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
            Label
        </label>

        <input 
            type="text"
            name="label"
            value="<?= e($field['label']) ?>"
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
            Field Type
        </label>

        <select name="field_type">

            <option value="text"
                <?= $field['field_type'] === 'text' ? 'selected' : '' ?>>
                Text
            </option>

            <option value="select"
                <?= $field['field_type'] === 'select' ? 'selected' : '' ?>>
                Select
            </option>

            <option value="checkbox"
                <?= $field['field_type'] === 'checkbox' ? 'selected' : '' ?>>
                Checkbox
            </option>

        </select>


        <br>


        <label>
            Required
        </label>

        <input
            type="checkbox"
            name="required"
            value="1"
            <?= $field['required'] ? 'checked' : '' ?>
        >


        <br>


        <label>
            Order
        </label>

        <input
            type="number"
            name="sort_order"
            value="<?= e($field['sort_order']) ?>"
            required
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