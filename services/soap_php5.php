<?php
include_once("../include/configuration.php");

$server = new SoapServer('pog.wsdl');
$server->setClass('ServiceClass');
$server->handle();


class ServiceClass
{

	/**
	 * Fetches the current POG version. Can be used to detect for upgrades
	 *
	 * @return base64 encoded string
	 */
	function GetGeneratorVersion()
	{
		require_once("../include/configuration.php");
		return base64_encode($GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber']);
	}

	/**
	 * Generates the appropriate object from supplied attributeList, typeList etc.
	 *
	 * @param string $objectName
	 * @param array $attributeList
	 * @param array $typeList
	 * @param string $language
	 * @param string $wrapper
	 * @param string $pdoDriver
	 * @return base64 encoded string
	 */
	function GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver, $classList)
	{
		require_once ("../include/configuration.php");
		require_once ("../include/class.misc.php");


		//added these so that POG would still generate something even if invalid variables are passed
		//this is so that users see something being generated even if they don't fill in the object fields
		if ($objectName == null)
		{
			$objectName = '';
		}
		if ($attributeList == null)
		{
			$attributeList = array();
		}
		if ($typeList == null)
		{
			$attributeList = array();
		}
		if ($language == null)
		{
			$language = '';
		}
		if ($wrapper == null)
		{
			$wrapper = '';
		}
		if ($pdoDriver == null)
		{
			$pdoDriver = '';
		}
		if ($classList == null)
		{
			$classList = array();
		}

		if (strtoupper($wrapper) == "PDO")
		{
			require_once "../object_factory/class.object".$language.strtolower($wrapper).$pdoDriver.".php";
		}
		else
		{

			if  (strtolower($language) == "php4")
			{
				require_once "../object_factory/class.objectphp4pogmysql.php";
			}
			else
			{
				require_once "../object_factory/class.objectphp5pogmysql.php";
			}
		}
		$object = new Object($objectName,$attributeList,$typeList,$pdoDriver, $language, $classList);
		$object->BeginObject();
		$object->CreateMagicGetterFunction();
		$object->CreateConstructor();
		$object->CreateGetFunction();
		$object->CreateGetListFunction();
		$object->CreateSaveFunction((in_array("HASMANY", $typeList) || in_array("JOIN", $typeList)));
		$object->CreateSaveNewFunction((in_array("HASMANY", $typeList) || in_array("JOIN", $typeList)));
		$object->CreateDeleteFunction((in_array("HASMANY", $typeList) || in_array("JOIN", $typeList)));
		$object->CreateDeleteListFunction((in_array("HASMANY", $typeList) || in_array("JOIN", $typeList)));

		$i = 0;
		foreach ($typeList as $type)
		{
			if ($type == "HASMANY")
			{
				$object->CreateGetChildrenFunction($attributeList[$i], $classList[$i]);
				$object->CreateSetChildrenFunction($attributeList[$i]);
				$object->CreateAddChildFunction($attributeList[$i]);
			}
			else if ($type == "BELONGSTO")
			{
				$object->CreateGetParentFunction($attributeList[$i], $classList[$i]);
				$object->CreateSetParentFunction($attributeList[$i]);
			}
			else if ($type == "JOIN")
			{
				$object->CreateSetAssociationsFunction($attributeList[$i]);
				$object->CreateGetAssociationsFunction($attributeList[$i]);
				$object->CreateAddAssociationFunction($attributeList[$i]);
			}
			$i++;
		}

		$object->EndObject();
		return base64_encode($object->string);
	}

	/**
	 * Generates the mapping object (for Many-Many relations)
	 *
	 * @param unknown_type $objectName1
	 * @param unknown_type $objectName2
	 * @param unknown_type $language
	 * @param unknown_type $wrapper
	 * @param unknown_type $pdoDriver
	 * @return unknown
	 */
	function GenerateMapping($objectName1, $objectName2, $language, $wrapper, $pdoDriver)
	{
		require_once ("../include/configuration.php");
		require_once ("../include/class.misc.php");

		if (strtoupper($wrapper) == "PDO")
		{
			require_once "../object_factory/class.objectmapping".$language.strtolower($wrapper).$pdoDriver.".php";
		}
		else
		{
			if  (strtolower($language) == "php4")
			{
				require_once "../object_factory/class.objectmappingphp4pogmysql.php";
			}
			else
			{
				require_once "../object_factory/class.objectmappingphp5pogmysql.php";
			}
		}

		$array = array($objectName1, $objectName2);
		sort($array);
		$object = new ObjectMap($array[0], $array[1]);
		$object->BeginObject();
		$object->CreateAddMappingFunction();
		$object->CreateRemoveMappingFunction();
		$object->CreateSaveFunction();
		$object->EndObject();

		return base64_encode($object->string);
	}

	/**
	 * Generates the appropriate object from `proprietary format` of @link
	 * An @link looks like this: http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=alliever&attributeList=array (  0 => 'firstName',  1 => 'lastName',  2 => 'description',  3 => 'gender',  4 => 'Country',  5 => 'over18',)&typeList=array (  0 => 'VARCHAR(255)',  1 => 'VARCHAR(255)',  2 => 'TEXT',  3 => 'enum(\\\'male\\\',\\\'female\\\')',  4 => 'enum(\\\'Mauritius\\\', \\\'Canada\\\', \\\'Singapore\\\')',  5 => 'enum(\\\'yes\\\')',)
	 * @param (urlencoded)string $link
	 * @return base64 encoded string
	 */
	function GenerateObjectFromLink($link)
	{
		$link = explode('?', $link);

		$linkParts = explode('&', $link[1]);
		for ($i = 0; $i < sizeof($linkParts); $i++)
		{
			$arguments = split('[^ ]=', $linkParts[$i]);
			$value = trim(stripcslashes(urldecode($arguments[1])));

			if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "attributeLis")
			{
				eval ("$".$arguments[0]." = ".stripcslashes(urldecode($value)).";");
			}
			else if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "typeLis")
			{
				if (strpos(strtolower($value), "enum") == false && strpos(strtolower($value), "set") == false)
				{
					eval ("$".$arguments[0]." = ".urldecode($value).";");
				}
				else
				{
					$typeLis = array();
					$value_parts = explode('=>', $value);

					for($j = 1; $j < sizeof($value_parts); $j++)
					{
						$value_part = $value_parts[$j];
						if (strpos(strtolower($value_part), "enum") != false)
						{
							$val = explode("(", $value_part);
							$val = explode(")", $val[1]);
							$typeLis[] = "enum(".$val[0].")";
						}
						else if (strpos(strtolower($value_part), "set") != false)
						{
							$val = explode("(", $value_part);
							$val = explode(")", $val[1]);
							$typeLis[] = "set(".$val[0].")";
						}
						else
						{
							$val = explode("'", $value_part);
							$typeLis[] = $val[1];
						}
					}
				}
			}
			else
			{
				eval ("$".$arguments[0]." = '".$value."';");
			}
		}
		if (!isset($pdoDrive))
		{
			$pdoDrive = '';
		}
		$string = $this->GenerateObject($objectNam, $attributeLis, $typeLis, $languag, $wrappe, $pdoDrive);
		return $string;
	}

	/**
	 * Generates a pog 'package' which is essentially a multi-D array with folder names as keys and file contents as values.
	 * The package can be delivered across the network, modified, and then finally zipped when the time is right.
	 * An @link looks like this: http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=alliever&attributeList=array (  0 => 'firstName',  1 => 'lastName',  2 => 'description',  3 => 'gender',  4 => 'Country',  5 => 'over18',)&typeList=array (  0 => 'VARCHAR(255)',  1 => 'VARCHAR(255)',  2 => 'TEXT',  3 => 'enum(\\\'male\\\',\\\'female\\\')',  4 => 'enum(\\\'Mauritius\\\', \\\'Canada\\\', \\\'Singapore\\\')',  5 => 'enum(\\\'yes\\\')',)
	 * @param (urlencoded)string $link
	 * @return base64 encoded string
	 */
	function GeneratePackageFromLink($link)
	{

		$link = explode('?', $link);

		$linkParts = explode('&', $link[1]);
		for ($i = 0; $i < sizeof($linkParts); $i++)
		{
			$arguments = split('[^ ]=', $linkParts[$i]);
			$value = trim(stripcslashes(urldecode($arguments[1])));

			if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "attributeLis")
			{
				eval ("$".$arguments[0]." = ".stripcslashes(urldecode($value)).";");
			}
			else if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "typeLis")
			{
				if (strpos($value, "enum") == false && strpos($value, "set") == false)
				{
					eval ("$".$arguments[0]." = ".urldecode($value).";");
				}
				else
				{
					$typeLis = array();
					$value_parts = explode('=>', urldecode($value));
					for($j = 1; $j < sizeof($value_parts); $j++)
					{
						$value_part = $value_parts[$j];
						if (strpos($value_part, "enum") != false)
						{
							$val = explode("(", $value_part);
							$val = explode(")", $val[1]);
							$typeLis[] = "enum(".$val[0].")";
						}
						else if (strpos($value_part, "set") != false)
						{
							$val = explode("(", $value_part);
							$val = explode(")", $val[1]);
							$typeLis[] = "set(".$val[0].")";
						}
						else
						{
							$val = explode("'", $value_part);
							$typeLis[] = $val[1];
						}
					}
				}
			}
			else
			{
				$$arguments[0] = $value;
			}
			if (!isset($pdoDrive))
			{
				$pdoDrive = '';
			}
		}
		return $this->GeneratePackage($objectNam, $attributeLis, $typeLis, $languag, $wrappe, $pdoDrive);
	}

	/**
	 * Generates the appropriate configuration file
	 *
	 * @param string $wrapper
	 * @return base64 encoded string
	 */
	function GenerateConfiguration($wrapper = null, $pdoDriver = null, $db_encoding = 0)
	{
		require_once("../include/configuration.php");
		if ($db_encoding == "")
		{
			$db_encoding = 0;
		}
		if (strtoupper($wrapper) == "PDO")
		{
			$data = file_get_contents("../configuration_factory/configuration.".strtolower($pdoDriver).".php");
		}
		else
		{
			$data = file_get_contents("../configuration_factory/configuration.php");
		}
		$data = str_replace('&db_encoding', $db_encoding, $data);
		$data = str_replace('&soap', 'http://www.phpobjectgenerator.com/services/pog.wsdl', $data);
		$data = str_replace('&versionNumber', $GLOBALS['configuration']['versionNumber'], $data);
		$data = str_replace('&revisionNumber', $GLOBALS['configuration']['revisionNumber'], $data);
		$data = str_replace('&homepage', $GLOBALS['configuration']['homepage'], $data);

		return base64_encode($data);
	}

	/**
	 * Generates a pog 'package' which is essentially a multi-D array with folder names as keys and file contents as values.
	 * The package can be delivered across the network, modified, and then finally zipped when the time is right.
	 *
	 * @param string $objectName
	 * @param array $attributeList
	 * @param array $typeList
	 * @param string $language
	 * @param string $wrapper
	 * @param string $pdoDriver
	 */
	function GeneratePackage($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver = null, $db_encoding = 0, $classList)
	{
		require_once ("../include/configuration.php");
		require_once ("../include/class.misc.php");

		$package = array();
		$package["objects"] = array();
		$package["setup"] = array();
		$package["setup"]["setup_images"] = array();
		$package["setup"]["setup_library"] = array();

		//generate configuration file
		$package["configuration.example.php"] = $this->GenerateConfiguration($wrapper, $pdoDriver, $db_encoding);

		//generate objects

		if (strtolower($language) == "php4")
		{
			$data = file_get_contents("../object_factory/class.database.php4.php");
		}
		else if (strtolower($language) == "php5" || (strtolower($language) == "php5.1" && strtolower($wrapper) == "pog"))
		{
			$data = file_get_contents("../object_factory/class.database.php5.php");
		}
		else
		{
			$data = file_get_contents("../object_factory/class.database.php5.1.php");
		}
		$data = str_replace('&versionNumber', $GLOBALS['configuration']['versionNumber'], $data);
		$data = str_replace('&revisionNumber', $GLOBALS['configuration']['revisionNumber'], $data);
		$data = str_replace('&language', strtoupper($language), $data);
		$package["objects"]["class.database.php"] = base64_encode($data);

		$data = file_get_contents("../object_factory/class.pog_base.".strtolower($language).strtolower($wrapper).".php");
		$package["objects"]["class.pog_base.php"] = base64_encode($data);

		$package["objects"]["class.".strtolower($objectName).".php"] =  $this->GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver, $classList);

		$package["objects"]["ignore_objects.txt"] = "";

		//generate mapping object if necessary
		$misc = new Misc(array());
		foreach ($typeList as $key => $type)
		{
			if ($type == "JOIN")
			{
				$package["objects"]["class.".strtolower($misc->MappingName($objectName, $attributeList[$key])).".php"] = $this->GenerateMapping($objectName, $attributeList[$key], $language, $wrapper, $pdoDriver);
			}
		}

		//generate setup
		if (strtoupper($wrapper) == "PDO")
		{
			$data = file_get_contents("../setup_factory/setup.pdo.php");
		}
		else
		{
			$data = file_get_contents("../setup_factory/setup.php");
		}
		$package["setup"]["index.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/rpc.php");
		$package["setup"]["rpc.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/setup.css");
		$package["setup"]["setup.css"] = base64_encode($data);

		if (strtolower($language) == 'php4')
		{
			$data = file_get_contents("../setup_factory/setup_files/setup_miscphp4.php");
		}
		else
		{
			$data = file_get_contents("../setup_factory/setup_files/setup_misc.php");
		}
		$package["setup"]["setup_library"]["setup_misc.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/inc.header.php");
		$package["setup"]["setup_library"]["inc.header.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/inc.footer.php");
		$package["setup"]["setup_library"]["inc.footer.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/xPandMenu.php");
		$package["setup"]["setup_library"]["xPandMenu.php"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/xPandMenu.css");
		$package["setup"]["setup_library"]["xPandMenu.css"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/xPandMenu.js");
		$package["setup"]["setup_library"]["xPandMenu.js"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/authentication.php");
		$package["setup"]["setup_library"]["authentication.php"] = base64_encode($data);

		if (strtolower($language) == "php4")
		{
			//add nusoap library to the package if since php4 does not support soap natively
			$data = file_get_contents("./nusoap.php");
			$package["setup"]["setup_library"]["nusoap.php"] = base64_encode($data);
		}

		//add zip class to package
		$data = file_get_contents("../include/class.zipfile.php");
		$package["setup"]["setup_library"]["class.zipfile.php"] = base64_encode($data);

		//generate upgrade scripts
		if (strtolower($language) == "php4")
		{
			$data = file_get_contents("../setup_factory/upgrade.php4.php");
		}
		else
		{
			$data = file_get_contents("../setup_factory/upgrade.php5.php");
		}
		$package["setup"]["setup_library"]["upgrade.php"] = base64_encode($data);

		//read all setup image files
		$dir = opendir('../setup_factory/setup_files/setup_images/');
		while(($file = readdir($dir)) !== false)
		{
			if (substr(strtolower($file), strlen($file) - 4) === '.gif' || substr(strtolower($file), strlen($file) - 4) === '.jpg')
			{
				$data = file_get_contents("../setup_factory/setup_files/setup_images/$file");
				$package["setup"]["setup_images"][$file] = base64_encode($data);
			}
		}
		closedir($dir);

		$package["setup"]["data_initialization"] = array();

		//data initialization stuff
		$data = file_get_contents("../setup_factory/setup_files/data_initialization/data_initialization.sql");
		$package["setup"]["data_initialization"]["data_initialization.sql"] = base64_encode($data);

		$package["setup"]["data_initialization"]["additional_table_structures.sql"] = "";

		$data = file_get_contents("../setup_factory/setup_files/data_initialization/howto.txt");
		$package["setup"]["data_initialization"]["howto.txt"] = base64_encode($data);

		$data = file_get_contents("../setup_factory/setup_files/data_initialization/read_dump_lib.php");
		$package["setup"]["data_initialization"]["read_dump_lib.php"] = base64_encode($data);

		$package["plugins"] = array();

		if (strtolower($language) != "php4")
		{
			$data = file_get_contents("../plugin_factory/IPlugin.php");
			$package["plugins"]["IPlugin.php"] = base64_encode($data);
		}
	//	add default base64 plugin
		$data = file_get_contents("../plugin_factory/base64_install.sql");
		$package["plugins"]["base64_install.sql"] = base64_encode($data);
		$data = file_get_contents("../plugin_factory/base64_uninstall.sql");
		$package["plugins"]["base64_uninstall.sql"] = base64_encode($data);
		$data = file_get_contents("../plugin_factory/plugin.base64.php");
		$package["plugins"]["plugin.base64.php"] = base64_encode($data);

		return serialize($package);
	}

}
?>
