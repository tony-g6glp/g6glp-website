<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_contests');

$stmt = $pdo->query("
    SELECT *
    FROM contests
    ORDER BY name
");

$contests = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<h1>Contest Manager / Wizard</h1>

<p>
    <a href="new.php">+ New Contest</a>
</p>


<table>

<tr>
    <th>Name</th>
    <th>ID</th>
    <th>Cabrillo</th>
    <th>Active</th>
    <th>Action</th>
</tr>


<?php foreach ($contests as $contest): ?>

<tr>

<td>
    <?= e($contest['name']) ?>
</td>

<td>
    <?= e($contest['contest_id']) ?>
</td>

<td>
    <?= e($contest['cabrillo_name']) ?>
</td>

<td>
    <?= $contest['active'] ? 'Yes' : 'No' ?>
</td>

<td>

    <a href="edit.php?id=<?= $contest['id'] ?>">
        Edit Contest
    </a>

    |

    <a href="headers.php?id=<?= $contest['id'] ?>">
        Open Wizard
    </a>

</td>

</tr>

<?php endforeach; ?>


</table>
</div>

<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>