
<div class="card">

<?php if (!empty($post['featured_image'])): ?>

<?php
$thumb = pathinfo(
    $post['featured_image'],
    PATHINFO_FILENAME
) . '_thumb.jpg';
?>

<img
src="/g6glp/uploads/posts/thumbs/<?= e($thumb) ?>"
alt="<?= e($post['title']) ?>"
class="post-thumb"
>

<?php endif; ?>


<h2><?= e($post['title']) ?></h2>


<p>
Category:

<?php if (!empty($post['category_name'])): ?>

<a href="category.php?slug=<?= urlencode($post['category_slug']) ?>">
<?= e($post['category_name']) ?>
</a>

<?php else: ?>

Uncategorised

<?php endif; ?>

</p>

<p>
<?= nl2br(e(substr($post['content'], 0, 250))) ?>

<?php if (strlen($post['content']) > 250): ?>
...
<?php endif; ?>

</p>


<p>
<small>
Published:
<?= e(date('d M Y', strtotime($post['created_at']))) ?>
</small>
</p>


<p>
<a href="post.php?slug=<?= urlencode($post['slug']) ?>">
Read more...
</a>
</p>


</div>
