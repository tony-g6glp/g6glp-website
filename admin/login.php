<?php

require_once __DIR__ . '/../include/bootstrap.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['user'] ?? '');
    $password = $_POST['pass'] ?? '';


    $stmt = $pdo->prepare("
        SELECT *
        FROM admin_users
        WHERE username = ?
        AND active = 1
        LIMIT 1
    ");

    $stmt->execute([$username]);

    $user = $stmt->fetch();


    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit;

    } else {

        $error = "Invalid login";

    }
}

?>

<!DOCTYPE html>
<html>

<head>
<title>Admin Login</title>

<link href="/g6glp/include/css.css" rel="stylesheet">

</head>

<body>

<?php include __DIR__ . '/../include/header.php'; ?>

<?php include __DIR__ . '/../include/nav.php'; ?>


<div class="container">

<div class="card login-card">

<h2>Admin Login</h2>


<?php if ($error): ?>

<p class="error">
<?= e($error) ?>
</p>

<?php endif; ?>


<form method="post">

<p>User</p>

<input 
type="text"
name="user"
>


<p>Password</p>

<input
type="password"
name="pass"
>


<br><br>

<button type="submit">
Login
</button>


</form>

</div>

</div>


<?php include __DIR__ . '/../include/footer.php'; ?>


</body>
</html>