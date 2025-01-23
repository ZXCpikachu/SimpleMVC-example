<h1><?php echo $results['pageTitle']; ?></h1>

<form method="post" 
      action="<?= \ItForFree\SimpleMVC\Router\WebRouter::link($results['categories']->id ? 'admin/editCategory' : 'admin/newCategory') . ($results['categories']->id ? '&categoryId=' . $results['categories']->id : '') ?>">

    <?php if ($results['categories']->id): ?>
        <input type="hidden" name="categoryId" value="<?php echo $results['categories']->id ?>" />
    <?php endif; ?>

    <?php if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <ul>
        <li>
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" 
                   value="<?php echo htmlspecialchars($results['categories']->name ?? '') ?>" />
        </li>

        <li>
            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Brief description of the category" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars($results['categories']->description ?? '') ?></textarea>
        </li>
    </ul>

    <div class="buttons">
        <input type="submit" name="saveChanges" value="<?= $results['categories']->id ? 'Save Changes' : 'Create Category' ?>" />
        <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
</form>

<?php if ($results['categories']->id): ?>
    <p>
        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Admin/deleteCategory') ?>&amp;categoryId=<?php echo $results['categories']->id ?>" onclick="return confirm('Delete This Category?')">
            Delete This Category
        </a>
    </p>
<?php endif; ?>
