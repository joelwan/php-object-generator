<?php
global $configuration;
$configuration['soap'] = "http://localhost:8888/pog/services/pog.wsdl";
$configuration['homepage'] = "http://localhost:8888/pog";
$configuration['revisionNumber'] = "";
$configuration['versionNumber'] = "3.0";

$configuration['setup_password'] = '';


// to enable automatic data encoding, run setup, go to the manage plugins tab and install the base64 plugin.
// then set db_encoding = 1 below.
// when enabled, db_encoding transparently encodes and decodes data to and from the database without any
// programmatic effort on your part.
$configuration['db_encoding'] = 0;

// edit the information below to match your database settings

$configuration['db']	= 'feedmap'; 		//	database name
$configuration['host']	= 'localhost';	//	database host
$configuration['user']	= 'root';		//	database user
$configuration['pass']	= 'root';		//	database password
$configuration['port'] 	= '8889';		//	database port


//proxy settings - if you are behnd a proxy, change the settings below
$configuration['proxy_host'] = false;
$configuration['proxy_port'] = false;
$configuration['proxy_username'] = false;
$configuration['proxy_password'] = false;

//plugin settings
$configuration['plugins_path'] = '/Applications/MAMP/htdocs/pog/tests/php5/app/plugins';  //absolute path to plugins folder, e.g c:/mycode/test/plugins or /home/phpobj/public_html/plugins


?>