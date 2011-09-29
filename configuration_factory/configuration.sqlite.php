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

$configuration['pdoDriver']= 'sqlite';
$configuration['setup_password'] = '';

//db_encoding=1 is highly recommended unless you know what you're doing
$configuration['db_encoding'] = &db_encoding;

// edit the information below to match your database settings

$configuration['sqliteDatabase']= 'C:\code\pog\test.db';
?>