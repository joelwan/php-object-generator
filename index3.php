<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/configuration.php";
include "./include/class.zipfile.php";
if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
{
	include "./services/nusoap.php";
}
if (isset($_SESSION['objectString']))
{
	$_GET = null;

	if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
	{
		$client = new soapclient($GLOBALS['configuration']['soap'], true);
		$attributeList = unserialize($_SESSION['attributeList']);
		$typeList = unserialize($_SESSION['typeList']);
		$params = array(
			    'objectName' 	=> $_SESSION['objectName'],
			    'attributeList' => $attributeList,
			    'typeList'      => $typeList,
			    'language'      => $_SESSION['language'],
			    'wrapper'       => $_SESSION['wrapper'],
			    'pdoDriver'     => $_SESSION['pdoDriver'],
			    'db_encoding' 	=> "0"
			);
		$package = unserialize($client->call('GeneratePackage', $params));
	}
	else if ($GLOBALS['configuration']['soapEngine'] == "phpsoap")
	{
		$client = new SoapClient('services/pog.wsdl', array('cache_wsdl' => 0));
		$attributeList = unserialize($_SESSION['attributeList']);
		$typeList = unserialize($_SESSION['typeList']);
		$objectName = $_SESSION['objectName'];
		$language = $_SESSION['language'];
		$wrapper = $_SESSION['wrapper'];
		$pdoDriver = $_SESSION['pdoDriver'];
		$classList = unserialize($_SESSION['classList']);
		$dbEncoding = "0";

		try
		{
			$package = unserialize($client->GeneratePackage($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver, $dbEncoding, $classList));
		}

		catch (SoapFault $e)
		{
			echo "Error: {$e->faultstring}";
		}
	}
	$zipfile = new createZip();
	$zipfile -> addPOGPackage($package);
	$zipfile -> forceDownload("pog.".time().".zip");
	$_POST = null;
}
else
{
	header("Location:/");
}
?>