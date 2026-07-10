<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
Future role/permission system

Current roles:
- admin

Future roles may include:
- superadmin
- editor
- author
- contributor

When multi-user support is added,
permission checks should be handled here
rather than hard-coded in individual pages.
*/
function require_login() {
    if (!isset($_SESSION['logged_in'])) {
        redirect('/admin/login.php');
    }
}

function require_role($role)
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        die('Access denied');
    }
}
?>
