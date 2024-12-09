<div id="adminHeader">
    <h2>Widget News Admin</h2>
    <p>You are logged in as <b><?php echo $User->userName ?></b>.
        <a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/index')?>">Edit Articles</a> 
        <a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/listCategories')?>">Edit Categories</a>
	<a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/listSubcategories')?>">Edit Subcategories</a>
	<a href="<?= \ItForFree\SimpleMVC\Url::link('Admin/listUsers')?>">Edit Users</a>
        <a href="<?= \ItForFree\SimpleMVC\Url::link('Login/logout') ?>">Log out </a>
                
    </p>
</div>
