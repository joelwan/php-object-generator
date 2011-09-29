<?php
class LoadChildren extends POG_Base
{
	var $sourceObject;
	var $argv;
	var $version = '0.1';

	function Version()
	{
		return $this->version;
	}

	function LoadChildren($sourceObject, $argv)
	{
		$this->sourceObject = $sourceObject;
		$this->argv = $argv;
	}

	function Execute()
	{
		$this->LoadAChild($this->sourceObject);
	}

	function LoadAChild($object)
	{
		//get all child classes
		$allVars = array_keys($object->pog_attribute_type);
		foreach ($allVars as $var)
		{
			$dbAttributes = $object->GetFieldAttribute($var, 'db_attributes');
			if ($dbAttributes != null && sizeof($dbAttributes) > 1)
			{
				if ($dbAttributes[0] == 'OBJECT' && $dbAttributes[1] == 'HASMANY')
				{
					//found a child. load to memory
					eval ('$objectList = $object->Get'.$var.'List();');
					foreach ($objectList as $anObject)
					{
						eval ('$object->Add'.($var).'($anObject);');
						$this->LoadAChild($anObject);
					}
				}
			}

		}
	}

	function SetupRender()
	{
		echo 'Unit test not implemented';
	}

	function AuthorPage()
	{
		return null;
	}
}
