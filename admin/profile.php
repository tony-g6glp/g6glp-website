<?php

require_once __DIR__ . '/../include/admin.php';

$stmt = $pdo->prepare("
    SELECT username, email, role, created_at, last_login
    FROM admin_users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$_SESSION['user_id']]);

$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html>

<head>
<title>Profile</title>
<link href="/g6glp/include/css.css" rel="stylesheet">
</head>

<body>

<?php include __DIR__ . '/../include/header.php'; ?>

<?php include __DIR__ . '/../include/nav.php'; ?>


<div class="container">

<div class="card">

<h2>My Profile</h2>

<table>

<tr>
<th>Username</th>
<td><?= e($user['username']) ?></td>
</tr>

<tr>
<th>Email</th>
<td><?= e($user['email']) ?></td>
</tr>

<tr>
<th>Role</th>
<td><?= e($user['role']) ?></td>
</tr>

<tr>
<th>Account Created</th>
<td><?= e($user['created_at']) ?></td>
</tr>

<tr>
<th>Last Login</th>
<td><?= e($user['last_login']) ?></td>
</tr>

</table>

<br>

<a href="change_password.php">
Change Password
</a>

</div>

</div>


<?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>
