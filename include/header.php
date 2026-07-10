<header class="site-header">

    <div class="header-left">

        <h1>G6GLP CMS</h1>

        <p>
            Personal Website & Blog
        </p>

    </div>


    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

    <div class="header-user">

        <strong>
            <?= e($_SESSION['username']) ?>
        </strong>

        <br>

        <span>
            <?= e($_SESSION['role']) ?>
        </span>

        <br>

        <a href="/g6glp/admin/profile.php">
            Profile
        </a>

        |

        <a href="/g6glp/admin/logout.php">
            Logout
        </a>

    </div>

    <?php endif; ?>

</header>