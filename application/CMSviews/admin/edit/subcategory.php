
    <h1><?php echo $results['pageTitle'];?></h1>
    
    <form action="admin.php?action=<?php echo $results['formAction']?>"
          method="post">
    
        <input type="hidden" name="subcategoryId"
               value="<?php echo $results['subcategories']->id ?>"/>
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>
    
    <ul>
        <li>
            <label for="name">Subcategory Name</label>
            <input type="text" name="name" id="name"
                placeholder="Name of the subcategory" required autofocus maxlength="255"
                value="<?php echo htmlspecialchars( $results['subcategories']->name ?? '' )?>" />
            </li>

        <li>
            <label for="description">Description</label>
            <textarea name="description" id="description"
                placeholder="Brief description of the subcategory"><?php echo htmlspecialchars( $results['subcategories']->description ?? '' )?></textarea>
        </li>
        <li>
            <label for="category">Category</label>
            <select name="categoryId">
                <?php foreach ($results['categories'] as $category) { ?>
                    <option value="<?php echo $category->id ?>" 
    <?php echo ($category->id == $results['subcategories']->categoryId) ? "selected" : "" ?>>
    <?php echo htmlspecialchars($category->name) ?>
</option>

                <?php } ?>
            </select>

        </li>
    <ul>
        
    <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
        
    </form>
    
    <?php if ( $results['subcategories']->id ) { ?>
          <p><a href="admin.php?action=deleteSubcategory&amp;subcategoryId=<?php 
            echo $results['subcategories']->id ?>" 
            onclick="return confirm('Delete This Subcategory?')">
                  Delete This Subcategory
          </a></p>
    <?php } ?>