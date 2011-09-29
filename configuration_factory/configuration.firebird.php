<?php
//IMPORTANT:
//Rename this file to configuration.php after having inserted all the correct db information
/**
 * http://groups.google.com/group/mailing.www.php-dev/browse_thread/thread/6e0f0cea5cfb106a/83647b79c1867e06%2383647b79c1867e06?sa=X&oi=groupsr&start=0&num=3
 */
if (!isset($_SESSION))
{
	session_start();
}
global $configuration;
$configuration['soap'] = "&soap";
$configuration['homepage'] = "&homepage";
$configuration['revisionNumber'] = "&revisionNumber";
$configuration['versionNumber'] = "&versionNumber";

$configuration['pdoDriver']= 'firebird';
$configuration['setup_password'] = '';

//db_encoding=1 is highly recommended unless you know what you're doing
$configuration['db_encoding'] = &db_encoding;

// edit the information below to match your database settings

$configuration['db'] 	= 'localhost:C:\Inetpub\wwwroot\pog\TEST.fdb';	//	database path
$configuration['user'] 	= 'SYSDBA';										//	database user
$configuration['pass'] 	= 'masterkey';									//	database password
?>