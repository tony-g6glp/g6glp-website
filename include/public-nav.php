<nav class="public-nav">

<a href="/g6glp/">Home</a>

<a href="/g6glp/blog/">Blog</a>

<a href="/g6glp/software/">Software</a>

<a href="/g6glp/page.php?slug=membership">Club Membership</a>

<a href="/g6glp/page.php?slug=committee">Committee</a>

<a href="/g6glp/downloads/">
General Downloads
</a>

<?php
require_once __DIR__ . '/bootstrap.php';

$logged_in = !empty($_SESSION['logged_in']);

$is_admin = (
    $logged_in &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'admin'
);


$stmt = $pdo->prepare("
    SELECT
        title,
        slug,
        access_level
    FROM pages
    WHERE published = 1
    AND show_in_menu = 1
    AND
    (
        access_level = 'public'

        OR

        (
            access_level = 'registered'
            AND :logged_in = 1
        )

        OR

        (
            access_level = 'admin'
            AND :is_admin = 1
        )
    )

    ORDER BY
        sort_order,
        title
");


$stmt->execute([
    ':logged_in' => $logged_in ? 1 : 0,
    ':is_admin' => $is_admin ? 1 : 0
]);


$menu_pages = $stmt->fetchAll();



foreach ($menu_pages as $menu_page):

?>

<a href="/g6glp/page.php?slug=<?= e($menu_page['slug']) ?>">
<?= e($menu_page['title']) ?>
</a>

<?php endforeach; ?>

<a href="/g6glp/page.php?slug=contact">Contact</a>


<?php if (isset($_SESSION['user_id'])): ?>

<span class="admin-link">
    <a href="/g6glp/admin/">
        Admin
    </a>
</span>

<?php else: ?>

<span class="admin-link">
    <a href="/g6glp/admin/login.php">
        Admin
    </a>
</span>

<?php endif; ?>


</nav>