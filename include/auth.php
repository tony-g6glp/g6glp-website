<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function require_login() {
    if (!isset($_SESSION['logged_in'])) {
        redirect('/admin/login.php');
    }
}
?>
