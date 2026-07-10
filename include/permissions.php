<?php

// =================================
// Role / Permission Helpers
// =================================


function require_role($role)
{
    if (
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== $role
    ) {

        http_response_code(403);

        die('Access denied');

    }
}



function has_role($role)
{
    return (
        isset($_SESSION['role']) &&
        $_SESSION['role'] === $role
    );
}