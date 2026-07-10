<?php

require_once __DIR__ . '/../../include/admin.php';

require_role('admin');


$id = (int)($_GET['id'] ?? 0);

if ($id < 1) {
    die('Invalid user');
}


$stmt = $pdo->prepare("
    SELECT id, username
    FROM admin_users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch();


if (!$user) {
    die('User not found');
}


$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();


    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';


    if ($password === '') {

        $message = "Password is required.";

    } elseif ($password !== $confirm) {

        $message = "Passwords do not match.";

    } else {


        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $stmt = $pdo->prepare("
            UPDATE admin_users
            SET password_hash = ?
            WHERE id = ?
        ");


        $stmt->execute([
            $hash,
            $id
        ]);


        redirect('/admin/users/');

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>


<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<div class="card">


<h2>
Change Password for <?= e($user['username']) ?>
</h2>


<?php if ($message): ?>

<p class="error">
<?= e($message) ?>
</p>

<?php endif; ?>


<form method="post">


<?= csrf_field(); ?>


<p>
New Password
</p>

<input
type="password"
name="password"
required
>


<p>
Confirm Password
</p>

<input
type="password"
name="confirm"
required
>


<br><br>


<button type="submit">
Update Password
</button>


</form>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>