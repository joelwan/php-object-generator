<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `plugin` (
	`pluginid` int(11) NOT NULL auto_increment,
	`name` VARCHAR(255) NOT NULL,
	`author` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	`code` TEXT NOT NULL,
	`version` VARCHAR(255) NOT NULL,
	`emailaddress` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`active` TINYINT NOT NULL,
	`mysql` VARCHAR(255) NOT NULL,
	`php` VARCHAR(255) NOT NULL, PRIMARY KEY  (`pluginid`)) ENGINE=MyISAM;
*/

/**
* <b>plugin</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=plugin&attributeList=array+%28%0A++0+%3D%3E+%27name%27%2C%0A++1+%3D%3E+%27author%27%2C%0A++2+%3D%3E+%27description%27%2C%0A++3+%3D%3E+%27code%27%2C%0A++4+%3D%3E+%27version%27%2C%0A++5+%3D%3E+%27emailAddress%27%2C%0A++6+%3D%3E+%27password%27%2C%0A++7+%3D%3E+%27active%27%2C%0A++8+%3D%3E+%27mysql%27%2C%0A++9+%3D%3E+%27php%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A++3+%3D%3E+%27TEXT%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27TINYINT%27%2C%0A++8+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++9+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class plugin extends POG_Base
{
	var $pluginId = '';

	/**
	 * @var VARCHAR(255)
	 */
	var $name;

	/**
	 * @var VARCHAR(255)
	 */
	var $author;

	/**
	 * @var TEXT
	 */
	var $description;

	/**
	 * @var TEXT
	 */
	var $code;

	/**
	 * @var VARCHAR(255)
	 */
	var $version;

	/**
	 * @var VARCHAR(255)
	 */
	var $emailAddress;

	/**
	 * @var VARCHAR(255)
	 */
	var $password;

	/**
	 * @var TINYINT
	 */
	var $active;

	/**
	 * @var VARCHAR(255)
	 */
	var $mysql;

	/**
	 * @var VARCHAR(255)
	 */
	var $php;

	var $pog_attribute_type = array(
		"pluginId" => array('db_attributes' => array("NUMERIC", "INT")),
		"name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"author" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"description" => array('db_attributes' => array("TEXT", "TEXT")),
		"code" => array('db_attributes' => array("TEXT", "TEXT")),
		"version" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"emailAddress" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"password" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"active" => array('db_attributes' => array("NUMERIC", "TINYINT")),
		"mysql" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"php" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		);
	var $pog_query;


	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
/*	function __get($attribute, &$value)
	{
		@eval('$result = $this->_'.$attribute.';');
		if ($result == null)
		{
			$value = false;
		}
		$value = $result;
		return true;
	}*/

	function plugin($name='', $author='', $description='', $code='', $version='', $emailAddress='', $password='', $active='', $mysql='', $php='')
	{
		$this->name = $name;
		$this->author = $author;
		$this->description = $description;
		$this->code = $code;
		$this->version = $version;
		$this->emailAddress = $emailAddress;
		$this->password = $password;
		$this->active = $active;
		$this->mysql = $mysql;
		$this->php = $php;
	}


	/**
	* Gets object from database
	* @param integer $pluginId
	* @return object $plugin
	*/
	function Get($pluginId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `plugin` where `pluginid`='".intval($pluginId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->pluginId = $row["pluginid"];
			$this->name = $this->Unescape($row["name"]);
			$this->author = $this->Unescape($row["author"]);
			$this->description = $this->Unescape($row["description"]);
			$this->code = $this->Unescape($row["code"]);
			$this->version = $this->Unescape($row["version"]);
			$this->emailAddress = $this->Unescape($row["emailaddress"]);
			$this->password = $this->Unescape($row["password"]);
			$this->active = $this->Unescape($row["active"]);
			$this->mysql = $this->Unescape($row["mysql"]);
			$this->php = $this->Unescape($row["php"]);
		}
		return $this;
	}


	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..}
	* @param string $sortBy
	* @param boolean $ascending
	* @param int limit
	* @return array $pluginList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `plugin` ";
		$pluginList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query  = $this->pog_query . " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query  = $this->pog_query . " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query  = $this->pog_query . " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query  = $this->pog_query . "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query  = $this->pog_query . "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query  = $this->pog_query . "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "pluginid";
		}
		$this->pog_query  = $this->pog_query . " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$plugin = new $thisObjectName();
			$plugin->pluginId = $row['pluginid'];
			$plugin->name = $this->Unescape($row['name']);
			$plugin->author = $this->Unescape($row['author']);
			$plugin->description = $this->Unescape($row['description']);
			$plugin->code = $this->Unescape($row['code']);
			$plugin->version = $this->Unescape($row['version']);
			$plugin->emailAddress = $this->Unescape($row['emailaddress']);
			$plugin->password = $this->Unescape($row['password']);
			$plugin->active = $this->Unescape($row['active']);
			$plugin->mysql = $this->Unescape($row['mysql']);
			$plugin->php = $this->Unescape($row['php']);
			$pluginList[] = $plugin;
		}
		return $pluginList;
	}


	/**
	* Saves the object to the database
	* @return integer $pluginId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `pluginid` from `plugin` where `pluginid`='".$this->pluginId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `plugin` set
			`name`='".$this->Escape($this->name)."',
			`author`='".$this->Escape($this->author)."',
			`description`='".$this->Escape($this->description)."',
			`code`='".$this->Escape($this->code)."',
			`version`='".$this->Escape($this->version)."',
			`emailaddress`='".$this->Escape($this->emailAddress)."',
			`password`='".$this->Escape($this->password)."',
			`active`='".$this->Escape($this->active)."',
			`mysql`='".$this->Escape($this->mysql)."',
			`php`='".$this->Escape($this->php)."' where `pluginid`='".$this->pluginId."'";
		}
		else
		{
			$this->pog_query = "insert into `plugin` (`name`, `author`, `description`, `code`, `version`, `emailaddress`, `password`, `active`, `mysql`, `php` ) values (
			'".$this->Escape($this->name)."',
			'".$this->Escape($this->author)."',
			'".$this->Escape($this->description)."',
			'".$this->Escape($this->code)."',
			'".$this->Escape($this->version)."',
			'".$this->Escape($this->emailAddress)."',
			'".$this->Escape($this->password)."',
			'".$this->Escape($this->active)."',
			'".$this->Escape($this->mysql)."',
			'".$this->Escape($this->php)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->pluginId == "")
		{
			$this->pluginId = $insertId;
		}
		return $this->pluginId;
	}


	/**
	* Clones the object and saves it to the database
	* @return integer $pluginId
	*/
	function SaveNew()
	{
		$this->pluginId = '';
		return $this->Save();
	}


	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `plugin` where `pluginid`='".$this->pluginId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}


	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..}
	* @param bool $deep
	* @return
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `plugin` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query  = $pog_query . " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query  = $pog_query . " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query  = $pog_query . "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query  = $pog_query . "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
}//overload('plugin');
?>