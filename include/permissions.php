<?php

// =================================
// Role permissions
// =================================

function permissions()
{
    return [

        'admin' => [

            'manage_users',

            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',

            'create_media',
            'delete_media',

            'manage_categories',
            'manage_tags'

        ],


        'editor' => [

            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',

            'create_media',
            'delete_media'

        ],


        'author' => [

            'create_posts',
            'edit_own_posts'

        ]

    ];
}


// =================================
// Check permission
// =================================

function can($permission)
{
    if (!isset($_SESSION['role'])) {
        return false;
    }


    $role = $_SESSION['role'];

    $all = permissions();


    if (!isset($all[$role])) {
        return false;
    }


    return in_array(
        $permission,
        $all[$role]
    );
}


// =================================
// Require permission
// =================================

function require_permission($permission)
{
    if (!can($permission)) {

        http_response_code(403);
        die('Access denied');

    }
}