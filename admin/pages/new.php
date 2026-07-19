<?php

require_once __DIR__ . '/../../include/admin.php';

require_permission('create_pages');


$page = [
    'title' => '',
    'slug' => '',
    'content' => '',
    'published' => 1,
    'show_in_menu' => 1,
	'access_level' => 'public',
    'sort_order' => 0
];


include 'page_form.php';