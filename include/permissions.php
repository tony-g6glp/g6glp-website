<?php

// =================================
// Role Permissions
// =================================

function permissions()
{
    return [

        'admin' => [

            'manage_users',
            'manage_posts',
            'manage_media',
            'manage_categories',
            'manage_settings'

        ],


        'editor' => [

            'manage_posts',
            'manage_media',
            'manage_categories'

        ],


        'author' => [

            'create_posts',
            'edit_own_posts'

        ]

    ];
}



// =================================
// Check Permission
// =================================

function can($permission)
{

    if (!isset($_SESSION['role'])) {

        return false;

    }


    $role = $_SESSION['role'];


    $permissions = permissions();


    return in_array(
        $permission,
        $permissions[$role] ?? []
    );

}



// =================================
// Require Permission
// =================================

function require_permission($permission)
{

    if (!can($permission)) {

        http_response_code(403);

        die('Access denied');

    }

}



// =================================
// Existing Role Helpers
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