<h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <input type="hidden" name="articleId" value="<?php echo $articleId ?>">
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <ul>
        <li>
            <label for="title">Article Title</label>
            <input type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $title )?>" />
        </li>

        <li>
            <label for="summary">Article Summary</label>
            <textarea name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $summary )?></textarea>
        </li>

        <li>
            <label for="content">Article Content</label>
            <textarea name="content" id="content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $content )?></textarea>
        </li>

        <li>
            <label for="categoryId">Article Category</label>
            <select name="categoryId">
                <?php foreach ($results['categories'] as $category){ ?>
                    <option value="<?php echo $category->id?>"<?php echo ( $category->id == $categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
            </select>
        </li>

        <li>
            <label for="subcategoryId">Article Subcategory</label>
            <select name="subcategoryId">
                <?php foreach ($results['subcategories'] as $subcategory) { ?>
                    <option value="<?php echo $subcategory->id?>"<?php
                        echo ($subcategory->id == $subcategoryId) ? " selected" : "" ?>>
                        <?php echo htmlspecialchars($subcategory->name) ?>
                    </option>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="authors[]">Authors</label>
            <select name="authors[]">
                <option value="">Без автора</option>
                <?php foreach ($results['authors'] as $author) { ?>
                    <option value="<?php echo $author->id ?>"<?php
                        echo in_array($author->id, $authors) ? " selected" : "" ?>>
                        <?php echo htmlspecialchars($author->login) ?>
                    </option>
                <?php } ?>
            </select>
        </li>    
        <li>
            <label for="publicationDate">Publication Date</label>
            <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $publicationDate ? date( "Y-m-d", $publicationDate ) : "" ?>" />
        </li>

        <li>
            <label for="checkActivity">Active</label>
            <input type="checkbox" name="active" value="1" id="checkActivity" <?php echo $activeArticle ? 'checked' : '' ?>>
        </li>
    </ul>

    <div class="buttons">
        <input type="submit" name="saveChanges" value="Save Changes" />
        <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>
</form>

<?php if ($results['article']->id) { ?>
<p><a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/deleteArticle')?>&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">
			Delete This Article
		</a>
    </p>
<?php } ?>