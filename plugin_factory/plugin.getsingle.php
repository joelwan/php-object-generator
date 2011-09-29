<?php
class GetSingle
{
	var $sourceObject;
	var $argv;
	var $version = '0.1';

	function Version()
	{
		return $this->version;
	}

	function GetSingle($sourceObject, $argv)
	{
		$this->sourceObject = $sourceObject;
		$this->argv = $argv;
	}

	function Execute()
	{
		$objectName = get_class($this->sourceObject);
		$fcv_array = array();
		$sortBy = '';
		$ascending = true;
		$limit = 1;
		if (isset($this->argv[0]))
		{
			$fcv_array = $this->argv[0];
		}
		if (isset($this->argv[1]))
		{
			$sortBy = $this->argv[1];
		}
		if (isset($this->argv[2]))
		{
			$ascending = $this->argv[2];
		}
		if (isset($this->argv[3]))
		{
			$limit = $this->argv[3];
		}
		$object = new $objectName();
		$objectList = $object->GetList($fcv_array, $sortBy, $ascending, $limit);
		if (sizeof($objectList) > 0)
		{
			return $objectList[0];
		}
		return null;
	}

	function SetupRender()
	{
		return null;
	}

	function AuthorPage()
	{
		return null;
	}
}
