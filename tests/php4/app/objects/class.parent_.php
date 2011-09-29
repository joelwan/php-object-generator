<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `parent_` (
	`parent_id` int(11) NOT NULL auto_increment,
	`attribute` VARCHAR(255) NOT NULL, PRIMARY KEY  (`parent_id`));
*/

/**
* <b>parent_</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=parent_&attributeList=array+%28%0A++0+%3D%3E+%27object%27%2C%0A++1+%3D%3E+%27attribute%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27HASMANY%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class parent_ extends POG_Base
{
	var $parent_Id = '';

	/**
	 * @var private array of object objects
	 */
	var $_objectList;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $attribute;
	
	var $pog_attribute_type = array(
		"parent_Id" => array('db_attributes' => array("NUMERIC", "INT")),
		"object" => array('db_attributes' => array("OBJECT", "HASMANY")),
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
	
	function parent_($attribute='')
	{
		$this->_objectList = array();
		$this->attribute = $attribute;
	}
	
	
	/**
	* Gets object from database
	* @param integer $parent_Id 
	* @return object $parent_
	*/
	function Get($parent_Id)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `parent_` where `parent_id`='".intval($parent_Id)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->parent_Id = $row["parent_id"];
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
	* @return array $parent_List
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `parent_` ";
		if (sizeof($fcv_array) > 0)
		{
			$parent_List = Array();
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
			$sortBy = "parent_id";
		}
		$this->pog_query  = $this->pog_query . " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$parent_ = new $thisObjectName();
			$parent_->parent_Id = $row['parent_id'];
			$parent_->attribute = $this->Unescape($row['attribute']);
			$parent_List[] = $parent_;
		}
		return $parent_List;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $parent_Id
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `parent_id` from `parent_` where `parent_id`='".$this->parent_Id."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `parent_` set 
			`attribute`='".$this->Escape($this->attribute)."' where `parent_id`='".$this->parent_Id."'";
		}
		else
		{
			$this->pog_query = "insert into `parent_` (`attribute` ) values (
			'".$this->Escape($this->attribute)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->parent_Id == "")
		{
			$this->parent_Id = $insertId;
		}
		if ($deep)
		{
			foreach (array_keys($this->_objectList) as $key)
			{
				$object =& $this->_objectList[$key];
				$object->parent_Id = $this->parent_Id;
				$object->Save($deep);
			}
		}
		return $this->parent_Id;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $parent_Id
	*/
	function SaveNew($deep = false)
	{
		$this->parent_Id = '';
		return $this->Save($deep);
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete($deep = false, $across = false)
	{
		if ($deep)
		{
			$objectList = $this->GetObjectList();
			foreach ($objectList as $object)
			{
				$object->Delete($deep, $across);
			}
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `parent_` where `parent_id`='".$this->parent_Id."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array, $deep = false, $across = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep || $across)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				$connection = Database::Connect();
				$pog_query = "delete from `parent_` where ";
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
	}
	
	
	/**
	* Gets a list of object objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of object objects
	*/
	function GetObjectList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$object = new object();
		$fcv_array[] = array("parent_Id", "=", $this->parent_Id);
		$dbObjects = $object->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all object objects in the object List array. Any existing object will become orphan(s)
	* @return null
	*/
	function SetObjectList($list)
	{
		$this->_objectList = array();
		$existingObjectList = $this->GetObjectList();
		foreach ($existingObjectList as $object)
		{
			$object->parent_Id = '';
			$object->Save(false);
		}
		$this->_objectList =& $list;
	}
	
	
	/**
	* Associates the object object to this one
	* @return 
	*/
	function AddObject($object)
	{
		$object->parent_Id = $this->parent_Id;
		$found = false;
		foreach($this->_objectList as $object2)
		{
			if ($object->objectId > 0 && $object->objectId == $object2->objectId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_objectList[] =& $object;
		}
	}
}overload('parent_');
?>