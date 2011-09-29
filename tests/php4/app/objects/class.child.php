<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `child` (
	`childid` int(11) NOT NULL auto_increment,
	`objectid` int(11) NOT NULL,
	`attribute` VARCHAR(255) NOT NULL, INDEX(`objectid`), PRIMARY KEY  (`childid`));
*/

/**
* <b>child</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=child&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BELONGSTO%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class child extends POG_Base
{
	var $childId = '';

	/**
	 * @var INT(11)
	 */
	var $objectId;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $attribute;
	
	var $pog_attribute_type = array(
		"childId" => array('db_attributes' => array("NUMERIC", "INT")),
		"object" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"attribute" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		);
	var $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	function __get($attribute, &$value)
	{
		@eval('$result = $this->_'.$attribute.';');
		if ($result == null)
		{
			$value = false;
		}
		$value = $result;
		return true;
	}
	
	function child($attribute='')
	{
		$this->attribute = $attribute;
	}
	
	
	/**
	* Gets object from database
	* @param integer $childId 
	* @return object $child
	*/
	function Get($childId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `child` where `childid`='".intval($childId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->childId = $row["childid"];
			$this->objectId = $row["objectid"];
			$this->attribute = $this->Unescape($row["attribute"]);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $childList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `child` ";
		if (sizeof($fcv_array) > 0)
		{
			$childList = Array();
			$this->pog_query  = $this->pog_query . " where ";
			$connection = Database::Connect();
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
			$sortBy = "childid";
		}
		$this->pog_query  = $this->pog_query . " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$child = new $thisObjectName();
			$child->childId = $row['childid'];
			$child->objectId = $row['objectid'];
			$child->attribute = $this->Unescape($row['attribute']);
			$childList[] = $child;
		}
		return $childList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $childId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `childid` from `child` where `childid`='".$this->childId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `child` set 
			`objectid`='".$this->objectId."', 
			`attribute`='".$this->Escape($this->attribute)."' where `childid`='".$this->childId."'";
		}
		else
		{
			$this->pog_query = "insert into `child` (`objectid`, `attribute` ) values (
			'".$this->objectId."', 
			'".$this->Escape($this->attribute)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->childId == "")
		{
			$this->childId = $insertId;
		}
		return $this->childId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $childId
	*/
	function SaveNew()
	{
		$this->childId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `child` where `childid`='".$this->childId."'";
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
			$pog_query = "delete from `child` where ";
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
	
	
	/**
	* Associates the object object to this one
	* @return boolean
	*/
	function GetObject()
	{
		$object = new object();
		return $object->Get($this->objectId);
	}
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function SetObject(&$object)
	{
		$this->objectId = $object->objectId;
	}
}overload('child');
?>