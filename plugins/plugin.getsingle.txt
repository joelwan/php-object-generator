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
		return $object;
	}

	function SetupRender()
	{
		if ($this->PerformUnitTest() === false)
		{
			echo get_class($this).' failed unit test';
		}
		else
		{
			echo get_class($this).' passed unit test';
		}
	}

	function AuthorPage()
	{
		return null;
	}

	function PerformUnitTest()
	{
		//test w/o arguments
		//any object
		$objectNames = unserialize($_SESSION['objectNameList']);

		//try getting a count
		if (sizeof($objectNames) > 0)
		{
			$anyObject = $objectNames[0];
			include_once("../objects/class.".strtolower($anyObject).".php");
			$anyObjectInstance = new $anyObject();
			$obj1 = $anyObjectInstance->GetSingle();

			if ($obj1->{strtolower($anyObject).'Id'} == '')
			{
				return false;
			}
			return true;
		}

		//test w/ arguments

	}
}
