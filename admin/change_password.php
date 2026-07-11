<?php

require_once __DIR__ . '/../include/admin.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
verify_csrf();
	
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

   if ($new === '') {

		$error = "New password is required.";
	
	} elseif (strlen($new) < 8) {
	
		$error = "Password must be at least 8 characters.";
	
	} elseif ($new !== $confirm) {
	
		$error = "New passwords do not match.";
	
	} else {

        $stmt = $pdo->prepare("
            SELECT password_hash
            FROM admin_users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$_SESSION['user_id']]);

        $user = $stmt->fetch();


        if ($user && password_verify($current, $user['password_hash'])) {

            $hash = password_hash($new, PASSWORD_DEFAULT);


            $update = $pdo->prepare("
                UPDATE admin_users
                SET password_hash = ?
                WHERE id = ?
            ");

            $update->execute([
                $hash,
                $_SESSION['user_id']
            ]);


            $message = "Password changed successfully";


        } else {

            $error = "Current password is incorrect";

        }

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

<?php include __DIR__ . '/../include/header.php'; ?>
<?php include __DIR__ . '/../include/nav.php'; ?>


<div class="container">

<div class="card">

<h2>Change Password</h2>


<?php if ($message): ?>
<p class="success">
<?= e($message) ?>
</p>
<?php endif; ?>


<?php if ($error): ?>
<p class="error">
<?= e($error) ?>
</p>
<?php endif; ?>


<form method="post">
<?= csrf_field(); ?>

<p>Current Password</p>

<input 
type="password"
name="current_password"
autocomplete="current-password"
>


<p>New Password</p>

<input
type="password"
name="new_password"
autocomplete="new-password"
>


<p>Confirm New Password</p>
 
<input
type="password"
name="confirm_password"
autocomplete="new-password"
>

<br><br>

<button type="submit">
Change Password
</button>

</form>


</div>

</div>


<?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>