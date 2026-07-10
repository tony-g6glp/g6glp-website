<?php

require_once __DIR__ . '/../../include/admin.php';

require_role('admin');


$stmt = $pdo->query("
    SELECT
        id,
        username,
        email,
        role,
        active,
        created_at
    FROM admin_users
    ORDER BY username
");

$users = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html>

<head>

<title>Users</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>

<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<h2>Users</h2>

<?php if (!empty($_SESSION['message'])): ?>

<p class="message">
<?= e($_SESSION['message']) ?>
</p>

<?php unset($_SESSION['message']); ?>

<?php endif; ?>
<p>
<a href="new.php">
Add User
</a>
</p>


<table>


<tr>

<th>
Username
</th>

<th>
Email
</th>

<th>
Role
</th>

<th>
Status
</th>

<th>
Created
</th>

<th>
Actions
</th>

</tr>



<?php foreach ($users as $user): ?>


<tr>


<td>
<?= e($user['username']) ?>
</td>


<td>
<?= e($user['email']) ?>
</td>


<td>
<?= e($user['role']) ?>
</td>


<td>

<?= $user['active'] ? 'Active' : 'Disabled' ?>

</td>


<td>
<?= e($user['created_at']) ?>
</td>


<td>


<a href="edit.php?id=<?= e($user['id']) ?>">
Edit
</a>


|

<a href="password.php?id=<?= e($user['id']) ?>">
Password
</a>


|

<form method="post" action="delete.php" style="display:inline;">

<?= csrf_field(); ?>

<input
    type="hidden"
    name="id"
    value="<?= e($user['id']) ?>"
>


<button
    type="submit"
    onclick="return confirm('Delete user?')">
    Delete
</button>


</form>


</td>


</tr>


<?php endforeach; ?>


</table>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>