<h1><?php echo $results['pageTitle']?></h1>
<?php print_r($results['article'])?>
<form method="post" 
      action="<?= \ItForFree\SimpleMVC\Router\WebRouter::link($results['article']->id ? 'admin/editArticle' : 'admin/newArticle') . ($results['article']->id ? '&articleId=' . $results['article']->id : '') ?>">

    <?php if ($results['article']->id): ?>
        <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>" />
    <?php endif; ?>
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <ul>
        <li>
            <label for="title">Article Title</label>
            <input type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title ?? '')?>" />
        </li>

        <li>
            <label for="summary">Article Summary</label>
            <textarea name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary ?? ''  )?></textarea>
        </li>

        <li>
            <label for="content">Article Content</label>
            <textarea name="content" id="content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->content ?? ''  )?></textarea>
        </li>

        <li>
            <label for="categoryId">Article Category</label>
            <select name="categoryId">
                <?php foreach ($results['categories'] as $category){ ?>
                    <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
            </select>
        </li>

        <li>
            <label for="subcategoryId">Article Subcategory</label>
            <select name="subcategoryId">
                <?php foreach ($results['subcategories'] as $subcategory) { ?>
                    <option value="<?php echo $subcategory->id?>"<?php
                        echo ($subcategory->id == $results) ? " selected" : "" ?>>
                        <?php echo htmlspecialchars($subcategory->name) ?>
                    </option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="users[]">Authors</label>
            <select name="users[]">
                <option value="">Без автора</option>
                <?php foreach ($results['users'] as $author) { ?>
                    <option value="<?php echo $author->id ?>"<?php
                        echo ($author->id == $results) ? " selected" : "" ?>>
                        <?php echo htmlspecialchars($author->login) ?>
                    </option>
                <?php } ?>
            </select>
        </li>    
        <li>
            <label for="publicationDate">Publication Date</label>
            <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required value="<?php echo $results['article']->publicationDate ? date( 'Y-m-d', strtotime($results['article']->publicationDate)) : '' ?>" />

        </li>

        <li>
            <label for="checkActivity">Active</label>
            <input type="checkbox" name="active" value="1" id="checkActivity" <?php echo $results['article']->active ? 'checked' : '' ?>>
        </li>
    </ul>

    <div class="buttons">
        <input type="submit" name="saveChanges" value="Save Changes" />
        <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
</form>

<?php if ($results['article']->id) { ?>
    <p><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Admin/deleteArticle') ?>&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">
            Delete This Article
        </a>
    </p>
<?php } ?>
