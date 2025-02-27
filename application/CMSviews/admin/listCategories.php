<h1>Article Categories</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
    <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

<?php if ( isset( $results['statusMessage'] ) ) { ?>
    <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

<table>
    <tr>
        <th>Category</th>
    </tr>

<?php foreach ( $results['categories'] as $category ) { ?>

    <tr onclick="location='<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Admin/editCategory') ?>&amp;categoryId=<?php echo $category->id?>'">
        <td>
            <?php echo $category->name?>
        </td>
    </tr>

<?php } ?>

</table>

<p><?php echo $results['totalRows']?> category<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>

<p><a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Admin/newCategory') ?>">Add a New Category</a></p>

<?php include "templates/include/footer.php" ?>
