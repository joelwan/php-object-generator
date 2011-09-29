<?php
class GetCount extends POG_Base
{
	var $sourceObject;
	var $argv;
	var $version = '0.1';

	function Version()
	{
		return $this->version;
	}

	function GetCount($sourceObject, $argv)
	{
		$this->sourceObject = $sourceObject;
		$this->argv = $argv;
	}

	function Execute()
	{
		$objectName = get_class($this->sourceObject);

		$fcv_array = $this->argv[0];

		$sql = 'select count(*) as mycount from `'.strtolower($objectName).'`';

		if (sizeof($fcv_array) > 0)
		{
			$sql .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$sql .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$sql .= " AND ";
					}
					$fieldAttributes = $this->sourceObject->GetFieldAttribute($fcv_array[$i][0], 'db_attributes');
					if ($fieldAttributes != null && $fieldAttributes[0] != 'NUMERIC' && $fieldAttributes[0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$sql .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$sql .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$sql .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}


		$connection = Database::Connect();
		$cursor = Database::Reader($sql, $connection);
		while ($row = Database::Read($cursor))
		{
			$count = $row['mycount'];
		}
		return $count;
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
