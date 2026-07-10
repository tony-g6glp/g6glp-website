<nav class="public-nav">

<a href="/g6glp/">Home</a>

<a href="/g6glp/blog/">Blog</a>

<a href="/g6glp/software/">Software</a>

<a href="/g6glp/software/membership/">Club Membership</a>

<a href="/g6glp/about.php">About</a>

<a href="/g6glp/contact.php">Contact</a>


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