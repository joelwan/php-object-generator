<?php
include "configuration.php";
include "objects/class.database.php";
include "objects/class.plugin.php";

if ($_GET['id'] != ''){
	$plugin = new plugin();
	$plugin->Get($_GET['id']);

	if ($plugin->pluginId){
		header ("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename="plugin.'.strtolower($plugin->name).'.php"');
		echo stripslashes(base64_decode($plugin->code));

	}
	else{
		echo 'plugin not found';
	}


}
?>