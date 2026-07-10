<form method="post" enctype="multipart/form-data">

	<?= csrf_field(); ?>
    <p>Title</p>

    <input type="text"
           name="title"
           value="<?= e($post['title'] ?? '') ?>"
           required>


    <p>Content</p>

    <textarea name="content"
              rows="10"
              required><?= e($post['content'] ?? '') ?></textarea>

	<p>
	<label for="featured_image">Featured Image</label><br>
	
	<input
		type="file"
		id="featured_image"
		name="featured_image"
		accept="image/*">
	
	<?php if (!empty($post['featured_image'])): ?>
	
	<br><br>
	
	<img
		src="/g6glp/uploads/posts/<?= e($post['featured_image']) ?>"
		alt=""
		style="max-width:200px; height:auto;">
	
	<?php endif; ?>
</p>

    <p>Status</p>

    <select name="status">

        <option value="draft"
        <?= (($post['status'] ?? '') == 'draft') ? 'selected' : '' ?>>
            Draft
        </option>

        <option value="published"
        <?= (($post['status'] ?? '') == 'published') ? 'selected' : '' ?>>
            Published
        </option>

    </select>
<p>Category</p>

<select name="category_id">

<option value="">-- No Category --</option>

<?php foreach ($categories as $cat): ?>

<option value="<?= $cat['id'] ?>"
<?= (($post['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>

<?= e($cat['name']) ?>

</option>

<?php endforeach; ?>

</select>


<p>Tags</p>

<div class="tags">

<?php foreach ($tags as $tag): ?>

<label>

<input 
    type="checkbox"
    name="tags[]"
    value="<?= $tag['id'] ?>"
    <?= (isset($post_tags) && in_array($tag['id'], $post_tags)) ? 'checked' : '' ?>
>

<?= e($tag['name']) ?>

</label>

<br>

<?php endforeach; ?>

</div>

    <br><br>

    <button type="submit">
        <?= isset($post['id']) ? 'Update Post' : 'Save Post' ?>
    </button>

</form>