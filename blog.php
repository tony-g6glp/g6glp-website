<?php
require_once("include/bootstrap.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Blog Index</title>

<link href="include/css.css" rel="stylesheet" type="text/css">

</head>

<body>

<header>

<h1>Blog</h1>
<h2>Notes � Projects � Amateur Radio � Development</h2>

<p>
A collection of updates, technical notes, project progress,
ideas, and general observations from my work in software,
electronics, and amateur radio (G6GLP).
</p>

</header>

<?php include __DIR__ . '/include/public-nav.php'; ?>
<div class="container">

<section id="latest">

<h2 style="margin-bottom:30px;">Latest Posts</h2>

<div class="cards">

<div class="card">
<h3>Project Structure Refactor</h3>
<p>
Moving all shared assets into a central include directory
and standardising navigation across all pages.
</p>
<a href="/blog/post.php?id=1">Read More</a>
</div>

<div class="card">
<h3>Club Membership System Update</h3>
<p>
Improvements to renewal tracking and database structure
for better reporting and reliability.
</p>
<a href="/blog/post.php?id=2">Read More</a>
</div>

<div class="card">
<h3>Raspberry Pi Station Server</h3>
<p>
Using a Raspberry Pi as a lightweight web and logging server
for amateur radio operations.
</p>
<a href="/blog/post.php?id=3">Read More</a>
</div>

</div>

</section>

<section id="categories">

<h2 style="margin:40px 0 30px;">Categories</h2>

<div class="cards">

<div class="card">
<h3>Software Development</h3>
<p>PHP, MySQL, web applications, tools and automation.</p>
<a href="/blog/category.php?cat=software">View</a>
</div>

<div class="card">
<h3>Amateur Radio</h3>
<p>G6GLP operating, antennas, contesting and station setup.</p>
<a href="/blog/category.php?cat=radio">View</a>
</div>

<div class="card">
<h3>Electronics</h3>
<p>Arduino, ESP32, RF projects and hardware builds.</p>
<a href="/blog/category.php?cat=electronics">View</a>
</div>

<div class="card">
<h3>Infrastructure</h3>
<p>Linux servers, Raspberry Pi systems, hosting and networking.</p>
<a href="/blog/category.php?cat=infrastructure">View</a>
</div>

</div>

</section>

<section id="archive">

<h2 style="margin:40px 0 30px;">Archive</h2>

<div class="cards">

<div class="card">
<h3>2026</h3>
<p>Current year posts and active development logs.</p>
<a href="/blog/archive.php?year=2026">View</a>
</div>

<div class="card">
<h3>2025</h3>
<p>Previous year projects, upgrades and experiments.</p>
<a href="/blog/archive.php?year=2025">View</a>
</div>

<div class="card">
<h3>All Posts</h3>
<p>Complete chronological listing of all blog entries.</p>
<a href="/blog/archive.php">View</a>
</div>

</div>

</section>

</div>

<footer>

<p><strong>Tony Rider - G6GLP</strong></p>

<p>
Technical blog covering software, radio, electronics and systems development.
</p>

<p>
<a href="/index.php">? Home</a>
</p>

</footer>

</body>
</html>
