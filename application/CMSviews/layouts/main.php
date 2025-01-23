<?php
use ItForFree\SimpleMVC\Config;

$User = Config::getObject('core.user.class');
use application\models\Category;
?>
<?php include "includes/header.php"; ?>
<?php 
if( isset($_GET['route'])){
	if($User->userName == 'admin' && 
	   $User->userName != 'guest' && 
	   preg_match('/Admin/', $_GET['route']) == 1){ 
		  include "includes/adminHeader.php"; 
	}
}
?>
<?= $CONTENT_DATA ?>
<?php include "includes/footer.php"; ?>
