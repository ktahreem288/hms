<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'save_user'){
	$save = $crud->save_user();
	unset($crud);
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
	echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	unset($crud);
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
	echo $save;
}