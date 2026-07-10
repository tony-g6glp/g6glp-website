<?php

require_once __DIR__ . '/../../include/admin.php';

require_role('admin');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    die('Invalid request');

}


verify_csrf();


$id = (int)($_POST['id'] ?? 0);


if ($id < 1) {

    die('Invalid user');

}


// Stop deleting yourself

if ($id == $_SESSION['user_id']) {

    $_SESSION['message'] = "You cannot delete your own account.";

    redirect('/admin/users/');

}



// Check user exists

$stmt = $pdo->prepare("
    SELECT username, role
    FROM admin_users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch();


if (!$user) {

    $_SESSION['message'] = "User not found.";

    redirect('/admin/users/');

}



// Stop removing last admin

if ($user['role'] === 'admin') {


    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM admin_users
        WHERE role = 'admin'
    ");


    $admin_count = $stmt->fetchColumn();


    if ($admin_count <= 1) {

        $_SESSION['message'] =
            "Cannot delete the last administrator.";

        redirect('/admin/users/');

    }

}



// Delete user

$stmt = $pdo->prepare("
    DELETE FROM admin_users
    WHERE id = ?
");

$stmt->execute([$id]);


$_SESSION['message'] =
    "User deleted successfully.";


redirect('/admin/users/');