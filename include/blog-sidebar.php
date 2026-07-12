<?php 

// Get categories

$sidebar_categories = $pdo->query("
    SELECT name, slug
    FROM categories
    ORDER BY name
")->fetchAll();


// Get tags

$sidebar_tags = $pdo->query("
    SELECT name, slug
    FROM tags
    ORDER BY name
")->fetchAll();

?>


<div class="blog-sidebar">

<h3>
Categories
</h3>

<ul>

<?php foreach ($sidebar_categories as $sidebar_category): ?>

<li>
<a href="/g6glp/blog/category.php?slug=<?= urlencode($sidebar_category['slug']) ?>">
<?= e($sidebar_category['name']) ?>
</a>
</li>

<?php endforeach; ?>

</ul>


<h3>
Filter by tags
</h3>


<form method="get" action="/g6glp/blog/filter.php">

<h3>
Category
</h3>

<select name="category">

<option value="">
All categories
</option>

<?php foreach ($sidebar_categories as $sidebar_category): ?>

<option value="<?= e($sidebar_category['slug']) ?>">
<?= e($sidebar_category['name']) ?>
</option>

<?php endforeach; ?>

</select>
<h3>
Tags:
</h3>


<?php foreach ($sidebar_tags as $tag): ?>

<label>

<input
type="checkbox"
name="tags[]"
value="<?= e($tag['slug']) ?>"
>

<?= e($tag['name']) ?>

</label>

<br>

<?php endforeach; ?>


<br>


<button type="submit">
Filter
</button>


</form>


</div>