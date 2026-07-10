<?php

require_once __DIR__ . '/../../include/admin.php';

require_role('admin');


$id = (int)($_GET['id'] ?? 0);

if ($id < 1) {
    die('Invalid user');
}


// Get user

$stmt = $pdo->prepare("
    SELECT *
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


    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'author';
    $active = isset($_POST['active']) ? 1 : 0;


    // Prevent locking yourself out

    if ($id == $_SESSION['user_id']) {

        if (!$active) {

            $message = "You cannot disable your own account.";

        }

        if ($role !== 'admin') {

            $message = "You cannot remove your own administrator role.";

        }

    }


    if ($message === "") {


        $stmt = $pdo->prepare("
            UPDATE admin_users
            SET
                username = ?,
                email = ?,
                role = ?,
                active = ?
            WHERE id = ?
        ");


        $stmt->execute([

            $username,
            $email,
            $role,
            $active,
            $id

        ]);


        redirect('/admin/users/');

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Edit User</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>


<body>


<?php include __DIR__ . '/../../include/header.php'; ?>

<?php include __DIR__ . '/../../include/nav.php'; ?>


<div class="container">


<div class="card">


<h2>
Edit User
</h2>


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
value="<?= e($user['username']) ?>"
required
>


<p>
Email
</p>

<input
type="email"
name="email"
value="<?= e($user['email']) ?>"
>


<p>
Role
</p>

<select name="role">


<option value="admin"
<?= $user['role'] === 'admin' ? 'selected' : '' ?>>
Admin
</option>


<option value="editor"
<?= $user['role'] === 'editor' ? 'selected' : '' ?>>
Editor
</option>


<option value="author"
<?= $user['role'] === 'author' ? 'selected' : '' ?>>
Author
</option>


</select>


<p>

<label>

<input
type="checkbox"
name="active"
<?= $user['active'] ? 'checked' : '' ?>
>

Active

</label>

</p>


<br>


<button type="submit">
Save Changes
</button>


</form>


</div>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>

</html>