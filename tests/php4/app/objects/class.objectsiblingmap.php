<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `objectsiblingmap` (
	`objectid` int(11) NOT NULL,
	`siblingid` int(11) NOT NULL,INDEX(`objectid`, `siblingid`));
*/

/**
* <b>objectsiblingMap</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
class objectsiblingMap
{
	var $objectId = '';

	var $siblingId = '';

	var $pog_attribute_type = array(
		"objectId" => array('db_attributes' => array("NUMERIC", "INT")),
		"siblingId" => array('db_attributes' => array("NUMERIC", "INT")));
		var $pog_query;
	
	
	/**
	* Creates a mapping between the two objects
	* @param object $object 
	* @param sibling $otherObject 
	* @return 
	*/
	function AddMapping($object, $otherObject)
	{
		if (is_a($object, "object") && $object->objectId != '')
		{
			$this->objectId = $object->objectId;
			$this->siblingId = $otherObject->siblingId;
			return $this->Save();
		}
		else if (is_a($object, "sibling") && $object->siblingId != '')
		{
			$this->siblingId = $object->siblingId;
			$this->objectId = $otherObject->objectId;
			return $this->Save();
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	* Removes the mapping between the two objects
	* @param Object $object 
	* @param Object $object2 
	* @return 
	*/
	function RemoveMapping($object, $otherObject = null)
	{
		$connection = Database::Connect();
		if (is_a($object, "object"))
		{
			$this->pog_query = "delete from `objectsiblingmap` where `objectid` = '".$object->objectId."'";
			if ($otherObject != null && is_a($otherObject, "sibling"))
			{
				$this->pog_query .= " and `siblingid` = '".$otherObject->siblingId."'";
			}
		}
		else if (is_a($object, "sibling"))
		{
			$this->pog_query = "delete from `objectsiblingmap` where `siblingid` = '".$object->siblingId."'";
			if ($otherObject != null && is_a($otherObject, "object"))
			{
				$this->pog_query .= " and `objectid` = '".$otherObject->objectId."'";
			}
		}
		Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Physically saves the mapping to the database
	* @return 
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `objectid` from `objectsiblingmap` where `objectid`='".$this->objectId."' AND `siblingid`='".$this->siblingId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows == 0)
		{
			$this->pog_query = "insert into `objectsiblingmap` (`objectid`, `siblingid`) values ('".$this->objectId."', '".$this->siblingId."')";
		}
		return Database::InsertOrUpdate($this->pog_query, $connection);
	}
}
?>