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
            'manage_tags',

            'create_downloads',
            'edit_downloads',
            'delete_downloads',
            'manage_downloads',
			
			'manage_pages',
			'create_pages',
			'edit_pages',
			'edit_own_pages',
			'delete_pages',
			'use_adif_converter'

        ],


        'editor' => [

            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',

            'create_media',
            'delete_media',

            'create_downloads',
            'edit_downloads',
            'delete_downloads',
			
			'create_pages',
			'edit_pages',
			'edit_own_pages',
			'delete_pages',
			'use_adif_converter'

        ],


        'author' => [
		
			'create_posts',
			'edit_own_posts',
		
			'create_downloads',
			'edit_own_downloads',
			'use_adif_converter'
		
		],
		
		'guest' => [
			'use_adif_converter'
		],

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

function can_edit_post($post): bool
{
    // Admin/editor can edit all posts
    if (can('edit_posts')) {
        return true;
    }

    // Author can edit only their own posts
    if (
        can('edit_own_posts') &&
        $post['created_by'] == $_SESSION['user_id']
    ) {
        return true;
    }

    return false;
}


function can_delete_post($post): bool
{
    // Only users with delete_posts permission
    // can delete posts
    if (can('delete_posts')) {
        return true;
    }

    return false;
}
