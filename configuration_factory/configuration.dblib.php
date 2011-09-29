<?php
//IMPORTANT:
//Rename this file to configuration.php after having inserted all the correct db information
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;
$configuration['soap'] = "&soap";
$configuration['homepage'] = "&homepage";
$configuration['revisionNumber'] = "&revisionNumber";
$configuration['versionNumber'] = "&versionNumber";

$configuration['pdoDriver']= 'dblib';
$configuration['setup_password'] = '';

// edit the information below to match your database settings

$configuration['db']	= 'TEST'; 		//database name
$configuration['host'] 	= 'localhost'; 	//database host
$configuration['user'] 	= 'sa'; 		//database user
$configuration['pass'] 	= 'pass'; 		//database password
?>
