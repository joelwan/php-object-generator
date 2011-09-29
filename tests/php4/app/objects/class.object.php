<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `object` (
	`objectid` int(11) NOT NULL auto_increment,
	`parent_id` int(11) NOT NULL,
	`attribute` VARCHAR(255) NOT NULL, INDEX(`parent_id`), PRIMARY KEY  (`objectid`));
*/

/**
* <b>object</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0 / PHP4
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=object&attributeList=array+%28%0A++0+%3D%3E+%27child%27%2C%0A++1+%3D%3E+%27parent_%27%2C%0A++2+%3D%3E+%27attribute%27%2C%0A++3+%3D%3E+%27sibling%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27HASMANY%27%2C%0A++1+%3D%3E+%27BELONGSTO%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27JOIN%27%2C%0A%29
*/
include_once('class.pog_base.php');
include_once('class.objectsiblingmap.php');
class object extends POG_Base
{
	var $objectId = '';

	/**
	 * @var private array of child objects
	 */
	var $_childList;
	
	/**
	 * @var INT(11)
	 */
	var $parent_Id;
	
	/**
	 * @var VARCHAR(255)
	 */
	var $attribute;
	
	/**
	 * @var private array of sibling objects
	 */
	var $_siblingList;
	
	var $pog_attribute_type = array(
		"objectId" => array('db_attributes' => array("NUMERIC", "INT")),
		"child" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"parent_" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"attribute" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"sibling" => array('db_attributes' => array("OBJECT", "JOIN")),
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
	
	function object($attribute='')
	{
		$this->_childList = array();
		$this->attribute = $attribute;
		$this->_siblingList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $objectId 
	* @return object $object
	*/
	function Get($objectId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `object` where `objectid`='".intval($objectId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->objectId = $row["objectid"];
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
	* @return array $objectList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `object` ";
		if (sizeof($fcv_array) > 0)
		{
			$objectList = Array();
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
			$sortBy = "objectid";
		}
		$this->pog_query  = $this->pog_query . " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$object = new $thisObjectName();
			$object->objectId = $row['objectid'];
			$object->parent_Id = $row['parent_id'];
			$object->attribute = $this->Unescape($row['attribute']);
			$objectList[] = $object;
		}
		return $objectList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $objectId
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `objectid` from `object` where `objectid`='".$this->objectId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `object` set 
			`parent_id`='".$this->parent_Id."', 
			`attribute`='".$this->Escape($this->attribute)."'where `objectid`='".$this->objectId."'";
		}
		else
		{
			$this->pog_query = "insert into `object` (`parent_id`, `attribute`) values (
			'".$this->parent_Id."', 
			'".$this->Escape($this->attribute)."')";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->objectId == "")
		{
			$this->objectId = $insertId;
		}
		if ($deep)
		{
			foreach (array_keys($this->_childList) as $key)
			{
				$child =& $this->_childList[$key];
				$child->objectId = $this->objectId;
				$child->Save($deep);
			}
			foreach (array_keys($this->_siblingList) as $key)
			{
				$sibling =& $this->_siblingList[$key];
				$sibling->Save();
				$map = new objectsiblingMap();
				$map->AddMapping($this, $sibling);
			}
		}
		return $this->objectId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $objectId
	*/
	function SaveNew($deep = false)
	{
		$this->objectId = '';
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
			$childList = $this->GetChildList();
			foreach ($childList as $child)
			{
				$child->Delete($deep, $across);
			}
		}
		if ($across)
		{
			$siblingList = $this->GetSiblingList();
			$map = new objectsiblingMap();
			$map->RemoveMapping($this);
			foreach ($siblingList as $sibling)
			{
				$sibling->Delete($deep, $across);
			}
		}
		else
		{
			$map = new objectsiblingMap();
			$map->RemoveMapping($this);
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `object` where `objectid`='".$this->objectId."'";
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
				$pog_query = "delete from `object` where ";
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
	* Gets a list of child objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of child objects
	*/
	function GetChildList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$child = new child();
		$fcv_array[] = array("objectId", "=", $this->objectId);
		$dbObjects = $child->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all child objects in the child List array. Any existing child will become orphan(s)
	* @return null
	*/
	function SetChildList($list)
	{
		$this->_childList = array();
		$existingChildList = $this->GetChildList();
		foreach ($existingChildList as $child)
		{
			$child->objectId = '';
			$child->Save(false);
		}
		$this->_childList =& $list;
	}
	
	
	/**
	* Associates the child object to this one
	* @return 
	*/
	function AddChild($child)
	{
		$child->objectId = $this->objectId;
		$found = false;
		foreach($this->_childList as $child2)
		{
			if ($child->childId > 0 && $child->childId == $child2->childId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_childList[] =& $child;
		}
	}
	
	
	/**
	* Associates the parent_ object to this one
	* @return boolean
	*/
	function GetParent_()
	{
		$parent_ = new parent_();
		return $parent_->Get($this->parent_Id);
	}
	
	
	/**
	* Associates the parent_ object to this one
	* @return 
	*/
	function SetParent_(&$parent_)
	{
		$this->parent_Id = $parent_->parent_Id;
	}
	
	
	/**
	* Creates mappings between this and all objects in the sibling List array. Any existing mapping will become orphan(s)
	* @return null
	*/
	function SetSiblingList($siblingList)
	{
		$map = new objectsiblingMap();
		$map->RemoveMapping($this, null);
		$this->_siblingList =& $siblingList;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ..} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $objectList
	*/
	function GetSiblingList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$connection = Database::Connect();
		$sibling = new sibling();
		$siblingList = Array();
		$this->pog_query = "select distinct * from `sibling` a INNER JOIN `objectsiblingmap` m ON m.siblingid = a.siblingid where m.objectid = '$this->objectId' ";
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query  = $this->pog_query . " AND ";
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
					if (isset($sibling->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $sibling->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $sibling->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query  = $this->pog_query . "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query  = $this->pog_query . "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query  = $this->pog_query . "a.`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($sibling->pog_attribute_type[$sortBy]['db_attributes']) && $sibling->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $sibling->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE(a.$sortBy) ";
				}
				else
				{
					$sortBy = "a.$sortBy ";
				}
			}
			else
			{
				$sortBy = "a.$sortBy ";
			}
		}
		else
		{
			$sortBy = "a.siblingid";
		}
		$this->pog_query  = $this->pog_query . " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$cursor = Database::Reader($this->pog_query, $connection);
		while($row = Database::Read($cursor))
		{
			$sibling = new sibling();
			foreach ($sibling->pog_attribute_type as $attribute_name => $attrubute_type)
			{
				if ($attrubute_type['db_attributes'][1] != "HASMANY" && $attrubute_type['db_attributes'][1] != "JOIN")
				{
					if ($attrubute_type['db_attributes'][1] == "BELONGSTO")
					{
						$sibling->{strtolower($attribute_name).'Id'} = $row[strtolower($attribute_name).'id'];
						continue;
					}
					$sibling->{$attribute_name} = $this->Unescape($row[strtolower($attribute_name)]);
				}
			}
			$siblingList[] = $sibling;
		}
		return $siblingList;
	}
	
	
	/**
	* Associates the sibling object to this one
	* @return 
	*/
	function AddSibling($sibling)
	{
		if (is_a($sibling, "sibling"))
		{
			foreach (array_keys($sibling->_objectList) as $key)
			{
				$otherObject =& $sibling->_objectList[$key];
				if ($otherObject === $this)
				{
					return false;
				}
			}
			$found = false;
			foreach ($this->_siblingList as $sibling2)
			{
				if ($sibling->siblingId > 0 && $sibling->siblingId == $sibling2->siblingId)
				{
					$found = true;
					break;
				}
			}
			if (!$found)
			{
				$this->_siblingList[] =& $sibling;
			}
		}
	}
}overload('object');
?>