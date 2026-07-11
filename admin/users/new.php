<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('manage_users');


$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();


    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'editor';
    $active = isset($_POST['active']) ? 1 : 0;


    $allowed_roles = [
        'admin',
        'editor',
        'author'
    ];


    if (!in_array($role, $allowed_roles, true)) {

        $message = "Invalid role selected.";

    }


   if ($username === '' || $password === '') {

$message = "Username and password are required.";

} elseif (strlen($password) < 8) {

$message = "Password must be at least 8 characters.";

} else {





    // Check duplicate username

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM admin_users
        WHERE username = ?
    ");

    $stmt->execute([
        $username
    ]);


    if ($stmt->fetchColumn() > 0) {

        $message = "Username already exists.";

    } else {


        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
    );


	$stmt = $pdo->prepare("
		INSERT INTO admin_users
		(
			username,
			password_hash,
			email,
			role,
			active
		)
            VALUES (?, ?, ?, ?, ?)
     ");


	$stmt->execute([
		$username,
		$hash,
		$email,
		$role,
		$active
	]);


    redirect('/admin/users/');
    }


	$stmt = $pdo->prepare("
		INSERT INTO admin_users
		(
			username,
			password_hash,
			email,
			role,
			active
		)
	VALUES (?, ?, ?, ?, ?)
	");


	$stmt->execute([
		$username,
		$hash,
		$email,
		$role,
		$active
	]);


     redirect('/admin/users/');

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Add User</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>


<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<div class="card">


<h2>Add User</h2>


<?php if ($message): ?>

<p class="error">
<?= e($message) ?>
</p>

<?php endif; ?>


<form method="post">


<?= csrf_field(); ?>


<p>
Username
</p>

<input
type="text"
name="username"
required
>


<p>
Email
</p>

<input
type="email"
name="email"
>


<p>
Password
</p>

<input
type="password"
name="password"
required
>


<p>
Role
</p>

<select name="role">

<option value="admin">
Admin
</option>

<option value="editor" selected>
Editor
</option>

<option value="author">
Author
</option>

</select>


<p>

<label>

<input
type="checkbox"
name="active"
checked
>

Active

</label>

</p>


<br>


<button type="submit">
Create User
</button>


</form>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>