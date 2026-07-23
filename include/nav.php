<nav class="admin-nav">

<h4>Content</h4>

<a href="/g6glp/admin/">Dashboard</a>

<?php if (can('manage_posts') || can('create_posts')): ?>
<a href="/g6glp/admin/posts/">Posts</a>
<?php endif; ?>

<?php if (can('create_posts')): ?>
<a href="/g6glp/admin/posts/new.php">New Post</a>
<?php endif; ?>

<?php if (can('manage_pages') || can('create_pages')): ?>

<a href="/g6glp/admin/pages/">
Pages
</a>
<?php endif; ?>

<?php if (can('manage_categories')): ?>
<a href="/g6glp/admin/categories/">Categories</a>
<?php endif; ?>

<?php if (can('manage_tags')): ?>
<a href="/g6glp/admin/tags/">Tags</a>
<?php endif; ?>

<?php if (can('manage_downloads')): ?>
<a href="/g6glp/admin/download_categories/">
Download Categories
</a>

<?php endif; ?>
<?php if (can('manage_downloads') || can('create_downloads')): ?>

<a href="/g6glp/admin/downloads/">
Downloads
</a>

<?php endif; ?>

<?php if (can('manage_contests')): ?>

<a href="/g6glp/admin/contests/">
Contests
</a>

<?php endif; ?>
<?php if (
    can('create_media') ||
    can('delete_media')
): ?>

<a href="/g6glp/admin/media/">
Media
</a>

<?php endif; ?>

<a href="/g6glp/blog/">View Blog</a>

<h4>Administration</h4>

<?php if (can('manage_users')): ?>
<a href="/g6glp/admin/users/">Users</a>
<?php endif; ?>

<a href="/g6glp/admin/change_password.php">
Change Password
</a>

<a href="/g6glp/admin/profile.php">
Profile
</a>

<a href="/g6glp/admin/logout.php">
Logout
</a>

</nav>