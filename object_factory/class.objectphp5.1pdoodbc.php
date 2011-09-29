<?php
class Object
{
	var $string = "";
	var $sql = "";
	var $objectName = "";
	var $attributeList = array();
	var $typeList = array();
	var $separator = "\n\t";
	var $pdoDriver = "";

	// -------------------------------------------------------------
	function Object($objectName, $attributeList = '', $typeList ='', $pdoDriver='')
	{
		$this->objectName = $objectName;
		$this->attributeList = $attributeList;
		$this->typeList = $typeList;
		$this->pdoDriver = $pdoDriver;
	}

	// -------------------------------------------------------------
	function BeginObject()
	{
		$misc = new Misc(array());
		$this->string = "<?php\n";
		$this->string .= $this->CreatePreface();
		foreach ($this->typeList as $key => $type)
		{
			if ($type == "JOIN")
			{
				$this->string .= "\ninclude_once('class.".strtolower($misc->MappingName($this->objectName, $this->attributeList[$key])).".php');";
			}
		}
		$this->string .= "\nclass ".$this->objectName."\n{\n\t";
		$this->string.="public \$".strtolower($this->objectName)."Id = '';\n\n\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var INT\n\t";
				$this->string .=" */\n\t";
				$this->string.="public $".strtolower($attribute)."Id = '';\n\t";
				$this->string.="\n\t";
			}
			else if ($this->typeList[$x] == "HASMANY" || $this->typeList[$x] == "JOIN")
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var private array of $attribute objects\n\t";
				$this->string .=" */\n\t";
				$this->string.="private \$_".strtolower($attribute)."List;\n\t";
				$this->string.="\n\t";
			}
			else
			{
				$this->string .="/**\n\t";
				$this->string .=" * @var ".stripcslashes($this->typeList[$x])."\n\t";
				$this->string .=" */\n\t";
				$this->string.="public $".$attribute.";\n\t";
				$this->string.="\n\t";
			}
			$x++;
		}
		//	create attribute => type array map
		//	needed for setup
		$this->string .= "public \$pog_attribute_type = array(\n\t\t";
		$this->string .= "\"".strtolower($this->objectName)."id\" => array(\"NUMERIC\", \"INT\"),\n\t\t";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "BELONGSTO" || $this->typeList[$x] == "HASMANY" || $this->typeList[$x] == "JOIN")
			{
				$this->string .= "\"$attribute\" => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '')."),\n\t\t";
			}
			else
			{
				$this->string .= "\"".strtolower($attribute)."\" => array(\"".$misc->InterpretType($this->typeList[$x])."\", \"".$misc->GetAttributeType($this->typeList[$x])."\"".(($misc->InterpretLength($this->typeList[$x]) != null) ?  ', "'.$misc->InterpretLength($this->typeList[$x]).'"' : '')."),\n\t\t";
			}
			$x++;
		}
		$this->string .= ");\n\t";
		$this->string .= "public \$pog_query;";
	}

	// -------------------------------------------------------------
	function EndObject()
	{
		$this->string .= "\n}\n?>";
	}

	// -------------------------------------------------------------
	function CreateConstructor()
	{
		$this->string .= "\n\t\n\tfunction ".$this->objectName."(";
		$i = 0;
		$j = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$i] != "BELONGSTO" && $this->typeList[$i] != "HASMANY" && $this->typeList[$i] != "JOIN")
			{
				if ($j == 0)
				{
					$this->string .= '$'.$attribute.'=\'\'';
				}
				else
				{
					$this->string .= ', $'.$attribute.'=\'\'';
				}
				$j++;
			}
			$i++;
		}
		$this->string .= ")\n\t{";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] == "HASMANY" || $this->typeList[$x] == "JOIN")
			{
				$this->string .="\n\t\t\$this->_".strtolower($attribute)."List = array();";
			}
			else if ($this->typeList[$x] != "BELONGSTO")
			{
				$this->string .= "\n\t\t\$this->".$attribute." = $".$attribute.";";
			}
			$x++;
		}
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSQLQuery()
	{
		switch ($this->pdoDriver)
		{
			case "odbc":
				$this->sql .= "\tCREATE TABLE ".strtolower($this->objectName)."(\n\t".strtolower($this->objectName)."id INT NOT NULL IDENTITY(1,1),";
				$x=0;
				foreach ($this->attributeList as $attribute)
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->sql .= "\n\t".strtolower($attribute)."id INT NOT NULL,";
					}
					else if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
					{
						$this->sql .= "\n\t".strtolower($attribute)." ".stripcslashes($this->typeList[$x])." NOT NULL,";
					}
					$x++;
				}
				$this->sql .= ");";
				break;
		}
	}

	// -------------------------------------------------------------
	function CreateComments($description='', $parameterDescriptionArray='', $returnType='')
	{
		$this->string .= "/**\n"
 		."\t* $description\n";
 		if ($parameterDescriptionArray != '')
 		{
	 		foreach ($parameterDescriptionArray as $parameter)
	 		{
	 			$this->string .= "\t* @param $parameter \n";
	 		}
 		}
	     $this->string .= "\t* @return $returnType\n"
	     ."\t*/\n";
	}

	// -------------------------------------------------------------
	function CreatePreface()
	{
		$this->string .= "/*\n\tThis SQL query will create the table to store your object.\n";
		$this->CreateSQLQuery();
		$this->string .= "\n".$this->sql."\n*/";
		$this->string .= "\n\n/**";
		$this->string .= "\n* <b>".$this->objectName."</b> class with integrated CRUD methods.";
		$this->string .= "\n* @author ".$GLOBALS['configuration']['author'];
		$this->string .= "\n* @version POG ".$GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber']." / PHP5.1 ODBC";
		$this->string .= "\n* @see http://www.phpobjectgenerator.com/plog/tutorials/46/pdo-odbc";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n* @link http://www.phpobjectgenerator.com/?language=php5.1&wrapper=pdo&pdoDriver=".$this->pdoDriver."&objectName=".urlencode($this->objectName)."&attributeList=".urlencode(var_export($this->attributeList, true))."&typeList=".urlencode(var_export($this->typeList, true));;
		$this->string .= "\n*/";
	}


	// Essential functions

	// -------------------------------------------------------------
	function CreateMagicGetterFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Getter for some private attributes",'',"mixed \$attribute");
		$this->string .= "\tpublic function __get(\$attribute)\n\t{";
		$this->string .= "\n\t\tif (isset(\$this->{\"_\".\$attribute}))";
	    $this->string .= "\n\t\t{";
	    $this->string .= "\n\t\t\treturn \$this->{\"_\".\$attribute};";
	    $this->string .= "\n\t\t}";
        $this->string .= "\n\t\telse";
       	$this->string .= "\n\t\t{";
       	$this->string .= "\n\t\t\treturn false;";
       	$this->string .= "\n\t\t}";
	    $this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSaveFunction($deep = false)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Saves the object to the database",'',"integer $".strtolower($this->objectName)."Id");
		if ($deep)
		{
			$this->string .= "\tfunction Save(\$deep = true)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Save()\n\t{";
		}
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\$count = 0;";
		$this->string .= "\n\t\t\t\$this->pog_query = \"select count(".strtolower($this->objectName)."id) as count from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id'\";";
		$this->string .= "\n\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$count = \$row['count'];";
		$this->string .= "\n\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tif (\$count == 1)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// update object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"update ".strtolower($this->objectName)." set ";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id = '\".\$this->".strtolower($attribute)."Id.\"'";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= strtolower($attribute)." = '\".\$this->".$attribute.".\"'";
						}
						else
						{
							$this->string .= strtolower($attribute)." = '\".\$this->Escape(\$this->".$attribute.").\"'";
						}
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id = '\".\$this->".strtolower($attribute)."Id.\"', ";
					}
					else
					{

						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= strtolower($attribute)." = '\".\$this->".$attribute.".\"', ";
						}
						else
						{
							$this->string .= strtolower($attribute)." = '\".\$this->Escape(\$this->".$attribute.").\"', ";
						}
					}
				}
			}
			$x++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= " where ".strtolower($this->objectName)."id = '$".strtolower($this->objectName)."Id')\";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t// insert object";
		$this->string .= "\n\t\t\t\t\$this->pog_query = \"insert into ".strtolower($this->objectName)." (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id";
					}
					else
					{
						$this->string .= strtolower($attribute);
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= strtolower($attribute)."id, ";
					}
					else
					{
						$this->string .= strtolower($attribute).", ";
					}
				}
			}
			$x++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= ") values (";
		$x=0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if ($x == (count($this->attributeList)-1))
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "'\".\$this->".strtolower($attribute)."Id.\"'";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "'\".\$this->".$attribute.".\"'";
						}
						else
						{
							$this->string .= "'\".\$this->Escape(\$this->".$attribute.").\"'";
						}
					}
				}
				else
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "'\".\$this->".strtolower($attribute)."Id.\"', ";
					}
					else
					{
						if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
						{
							$this->string .= "'\".\$this->".$attribute.".\"', ";
						}
						else
						{
							$this->string .= "'\".\$this->Escape(\$this->".$attribute.").\"', ";
						}
					}
				}
			}
			$x++;
		}
		if (substr($this->string, strlen($this->string) - 2) == ", ")
		{
			$this->string = substr($this->string, 0, strlen($this->string) - 2);
		}
		$this->string .= ")\";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$Database->query(\$this->pog_query);";
		$this->string .= "\n\t\t\tif (\$this->".strtolower($this->objectName)."Id == \"\")";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query = (\"select max(".strtolower($this->objectName)."id) as max from ".strtolower($this->objectName)."\");";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['max'];";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep)";
			$this->string .= "\n\t\t\t{";
			$i = 0;
			foreach ($this->typeList as $type)
			{
				if ($type == "HASMANY")
				{
					$this->string .= "\n\t\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst(strtolower($this->attributeList[$i]))."List();";
					$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Save(\$deep);";
					$this->string .= "\n\t\t\t\t}";
				}
				else if ($type == "JOIN")
				{
					$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Save();";
					$this->string .= "\n\t\t\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$i])."();";
					$this->string .= "\n\t\t\t\t\t\$map->AddMapping(\$this, \$".strtolower($this->attributeList[$i]).");";
					$this->string .= "\n\t\t\t\t}";
				}
				$i++;
			}
			$this->string .= "\n\t\t\t}";
		}
		$this->string .= "\n\t\t\treturn \$this->".strtolower($this->objectName)."Id;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSaveNewFunction($deep = false)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Clones the object and saves it to the database",'',"integer $".strtolower($this->objectName)."Id");
		if ($deep)
		{
			$this->string .="\tfunction SaveNew(\$deep = false)\n\t{";
		}
		else
		{
			$this->string .="\tfunction SaveNew()\n\t{";
		}
		$this->string .= "\n\t\t\$this->".strtolower($this->objectName)."Id = '';";
		if ($deep)
		{
			$this->string .= "\n\t\treturn \$this->Save(\$deep);";
		}
		else
		{
			$this->string .= "\n\t\treturn \$this->Save();";
		}
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateDeleteFunction($deep = false)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Deletes the object from the database",'',"integer \$affectedRows");
		if ($deep)
		{
			$this->string .= "\tfunction Delete(\$deep = false)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction Delete()\n\t{";
		}
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep)";
			$this->string .= "\n\t\t\t{";
			$i = 0;
			foreach ($this->typeList as $type)
			{
				if ($type == "HASMANY")
				{
					$this->string .= "\n\t\t\t\t$".strtolower($this->attributeList[$i])."List = \$this->Get".ucfirst(strtolower($this->attributeList[$i]))."List();";
					$this->string .= "\n\t\t\t\tforeach ($".strtolower($this->attributeList[$i])."List as $".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Delete(\$deep);";
					$this->string .= "\n\t\t\t\t}";
				}
				else if ($type == "JOIN")
				{
					$this->string .= "\n\t\t\t\t\$this->Get".ucfirst(strtolower($this->attributeList[$i]))."List();";
					$this->string .= "\n\t\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$i])."();";
					$this->string .= "\n\t\t\t\t\$map->RemoveMapping(\$this);";
					$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($this->attributeList[$i])."List as \$".strtolower($this->attributeList[$i]).")";
					$this->string .= "\n\t\t\t\t{";
					$this->string .= "\n\t\t\t\t\t\$".strtolower($this->attributeList[$i])."->Delete(\$deep);";
					$this->string .= "\n\t\t\t\t}";
				}
				$i++;
			}
			$this->string .= "\n\t\t\t}";
			if (in_array("JOIN", $this->typeList))
			{
				$this->string .= "\n\t\t\telse";
				$this->string .= "\n\t\t\t{";
				$j = 0;
				foreach ($this->typeList as $type)
				{
					if ($type == "JOIN")
					{
						$this->string .= "\n\t\t\t\t\$map = new ".$misc->MappingName($this->objectName, $this->attributeList[$j])."();";
						$this->string .= "\n\t\t\t\t\$map->RemoveMapping(\$this);";
					}
					$j++;
				}
				$this->string .= "\n\t\t\t}";
			}
		}
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\$this->pog_query = \"delete from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id'\";";
		$this->string .= "\n\t\t\t\$affectedRows = \$Database->query(\$this->pog_query);";
		$this->string .= "\n\t\t\tif (\$affectedRows != null)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn \$affectedRows;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn 0;";
		$this->string .= "\n\t\t\t}";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}


	// -------------------------------------------------------------
	function CreateDeleteListFunction($deep = false)
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Deletes a list of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","bool \$deep"));
		if ($deep)
		{
			$this->string .= "\tfunction DeleteList(\$fcv_array, \$deep = false)\n\t{";
		}
		else
		{
			$this->string .= "\tfunction DeleteList(\$fcv_array)\n\t{";
		}
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$indentation = "\n\t\t\t";
		if ($deep)
		{
			$this->string .= "\n\t\t\tif (\$deep)";
			$this->string .= "\n\t\t\t{";
			$this->string .= "\n\t\t\t\t\$objectList = \$this->GetList(\$fcv_array);";
			$this->string .= "\n\t\t\t\tforeach (\$objectList as \$object)";
			$this->string .= "\n\t\t\t\t{";
			$this->string .= "\n\t\t\t\t\t\$object->Delete(\$deep);";
			$this->string .= "\n\t\t\t\t}";
			$this->string .= "\n\t\t\t}";
			$this->string .= "\n\t\t\telse";
			$this->string .= "\n\t\t\t{";
			$indentation .= "\t";
		}
		$this->string .= $indentation."try";
		$this->string .= "$indentation{";
		$this->string .= "$indentation\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';port='.\$GLOBALS['configuration']['port'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";

		$this->string .= $indentation."\t\$Database = new DatabaseConnection();";
		$this->string .= $indentation."\t\$pog_query = \"delete from ".strtolower($this->objectName)." where \";";
		$this->string .= $indentation."\tfor (\$i=0, \$c=sizeof(\$fcv_array); \$i<\$c; \$i++)";
		$this->string .= $indentation."\t{";
		$this->string .= $indentation."\t\tif (sizeof(\$fcv_array[\$i]) == 1)";
		$this->string .= $indentation."\t\t{";
		$this->string .= $indentation."\t\t\t\$pog_query .= \" \".\$fcv_array[\$i][0].\" \";";
		$this->string .= $indentation."\t\t\tcontinue;";
		$this->string .= $indentation."\t\t}";
		$this->string .= $indentation."\t\telse";
		$this->string .= $indentation."\t\t{";
		$this->string .= $indentation."\t\t\tif (\$i > 0 && sizeof(\$fcv_array[\$i-1]) !== 1)";
		$this->string .= $indentation."\t\t\t{";
		$this->string .= $indentation."\t\t\t\t\$pog_query .= \" AND \";";
		$this->string .= $indentation."\t\t\t}";
		$this->string .= $indentation."\t\t\tif (isset(\$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])]) && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'NUMERIC' && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'SET')";
		$this->string .= $indentation."\t\t\t{";
		$this->string .= $indentation."\t\t\t\t\$pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$Database->Escape(\$fcv_array[\$i][2]).\"'\";";
		$this->string .= $indentation."\t\t\t}";
		$this->string .= $indentation."\t\t\telse";
		$this->string .= $indentation."\t\t\t{";
		$this->string .= $indentation."\t\t\t\t\$pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= $indentation."\t\t\t}";
		$this->string .= $indentation."\t\t}";
		$this->string .= $indentation."\t}";
		$this->string .= $indentation."\t\$Database->Query(\$pog_query);";
		$this->string .= "$indentation}";
		$this->string .= $indentation."catch(PDOException \$e)";
		$this->string .= "$indentation{";
		$this->string .= "$indentation\tthrow new Exception(\$e->getMessage());";
		$this->string .= "$indentation}";
		if ($deep)
		{
			$this->string .= "\n\t\t\t}";
		}
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Gets object from database",array("integer \$".strtolower($this->objectName)."Id"),"object \$".$this->objectName);
		$this->string .="\n\tfunction Get(\$".strtolower($this->objectName)."Id)\n\t{";
		$this->string .="\n\t\ttry";
		$this->string .="\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .="\n\t\t\t\$this->pog_query = \"select * from ".strtolower($this->objectName)." where ".strtolower($this->objectName)."id='\$".strtolower($this->objectName)."Id'\";";
		$this->string .="\n\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .="\n\t\t\t{";
		$this->string .="\n\t\t\t\t\$this->".strtolower($this->objectName)."Id = \$row['".strtolower($this->objectName)."id'];";
		$x = 0;
		foreach ($this->attributeList as $attribute)
		{
			if ($this->typeList[$x] != "HASMANY" && $this->typeList[$x] != "JOIN")
			{
				if (strtolower(substr($this->typeList[$x],0,4)) == "enum" || strtolower(substr($this->typeList[$x],0,3)) == "set" || strtolower(substr($this->typeList[$x],0,4)) == "date" || strtolower(substr($this->typeList[$x],0,4)) == "time" || $this->typeList[$x] == "BELONGSTO")
				{
					if ($this->typeList[$x] == "BELONGSTO")
					{
						$this->string .= "\n\t\t\t\t\$this->".strtolower($attribute)."Id = \$row['".strtolower($attribute)."id'];";
					}
					else
					{
						$this->string .= "\n\t\t\t\t\$this->".$attribute." = \$row['".strtolower($attribute)."'];";
					}
				}
				else
				{
					$this->string .= "\n\t\t\t\t\$this->".$attribute." = \$this->Unescape(\$row['".strtolower($attribute)."']);";
				}
			}
			$x++;
		}
		$this->string .="\n\t\t\t}";
		$this->string .="\n\t\t\treturn \$this;";
		$this->string .="\n\t\t}";
		$this->string .="\n\t\tcatch(PDOException \$e)";
		$this->string .="\n\t\t{";
		$this->string .="\n\t\t\tthrow new Exception(\$e->getMessage());";
		$this->string .="\n\t\t}";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetAllFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tfunction GetList(\$fcv_array, \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$sqlLimit = (\$limit != '' && \$sortBy == ''?\"TOP \$limit\":'');";
		$this->string .= "\n\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($this->objectName)."List = Array();";
		$this->string .= "\n\t\t\ttry";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':'.\$GLOBALS['configuration']['odbcDSN']);";
		$this->string .= "\n\t\t\t\t\$pog_query = \"select \$sqlLimit ".strtolower($this->objectName)."id from ".strtolower($this->objectName)." where \";";
		$this->string .= "\n\t\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array)-1; \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (isset(\$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])]) && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'NUMERIC' && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'SET')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' AND \";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"' AND \";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (isset(\$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])]) && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'NUMERIC' && \$this->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'SET')";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".".$this->objectName."::Escape(\$fcv_array[\$i][2]).\"' order by ".strtolower($this->objectName)."id asc\";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$pog_query .= strtolower(\$fcv_array[\$i][0]).\" \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"' order by ".strtolower($this->objectName)."id asc\";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\$thisObjectName = get_class(\$this);";
		$this->string .= "\n\t\t\t\tforeach (\$Database->query(\$pog_query) as \$row)";
		$this->string .= "\n\t\t\t\t{";
      	$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)." = new \$thisObjectName();";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."->Get(\$row['".strtolower($this->objectName)."id']);";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)."List[] = \$".strtolower($this->objectName).";";
   		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (\$sortBy != '')";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$f = '';";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($this->objectName)." = new \$thisObjectName();";
		$this->string .= "\n\t\t\t\t\tif (isset(\$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)]) && (\$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)][0] == \"NUMERIC\" || \$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)][0] == \"SET\"))";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$f = 'return \$".strtolower($this->objectName)."1->'.\$sortBy.' > \$".strtolower($this->objectName)."2->'.\$sortBy.';';";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse if (isset(\$".strtolower($this->objectName)."->pog_attribute_type[strtolower(\$sortBy)]))";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$f = 'return strcmp(strtolower(\$".strtolower($this->objectName)."1->'.\$sortBy.'), strtolower(\$".strtolower($this->objectName)."2->'.\$sortBy.'));';";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tusort(\$".strtolower($this->objectName)."List, create_function('\$".strtolower($this->objectName)."1, \$".strtolower($this->objectName)."2', \$f));";
		$this->string .= "\n\t\t\t\t\tif (!\$ascending)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$".strtolower($this->objectName)."List = array_reverse(\$".strtolower($this->objectName)."List);";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\tif (\$limit != '')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$limitParts = explode(',', \$limit);";
		$this->string .= "\n\t\t\t\t\t\tif (sizeof(\$limitParts) > 1)";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\treturn array_slice(\$".strtolower($this->objectName)."List, \$limitParts[0], \$limitParts[1]);";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\treturn array_slice(\$".strtolower($this->objectName)."List, 0, \$limit);";
		$this->string .= "\n\t\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\treturn \$".strtolower($this->objectName)."List;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tcatch(PDOException \$e)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\tthrow new Exception(\$e->getMessage());";;
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn null;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateEscapeFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("This function will always try to encode \$text to base64, except when \$text is a number. This allows us to Escape all data before they're inserted in the database, regardless of attribute type.",array(1=>"string \$text"),"base64_encoded \$text");
		$this->string .= "\tfunction Escape(\$text)";
		$this->string .= "\n\t{";
		$this->string .= "\n\t\tif (\$GLOBALS['configuration']['db_encoding'] && !is_numeric(\$text))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn base64_encode(\$text);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn mysql_escape_string(\$text);";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateUnescapeFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= "function Unescape(\$text)";
		$this->string .= "\n\t{";
		$this->string .= "\n\t\tif (\$GLOBALS['configuration']['db_encoding'] && !is_numeric(\$text))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn base64_decode(\$text);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn stripcslashes(\$text);";
		$this->string .= "\n\t}";
	}


	// Relations {1-1, 1-Many, Many-1} functions

	// -------------------------------------------------------------
	function CreateAddChildFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $child object to this one",'',"");
		$this->string .= "\tfunction Add".ucfirst(strtolower($child))."(&\$".strtolower($child).")\n\t{";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List[] = \$".strtolower($child).";";
		$this->string .= "\n\t\t\$".strtolower($child)."->".strtolower($this->objectName)."Id = \$this->".strtolower($this->objectName)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetChildrenFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Gets a list of $child objects associated to this one", array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array of $child objects");
		$this->string .= "\tfunction Get".ucfirst(strtolower($child))."List(\$fcv_array = array(), \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$".strtolower($child)." = new ".$child."();";
		$this->string .= "\n\t\t\$fcv_array[] = array(\"".strtolower($this->objectName)."Id\", \"=\", \$this->".strtolower($this->objectName)."Id);";
		$this->string .= "\n\t\t\$dbObjects = \$".strtolower($child)."->GetList(\$fcv_array, \$sortBy, \$ascending, \$limit);";
		$this->string .= "\n\t\tforeach (\$dbObjects as \$dbObject)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->Add".ucfirst(strtolower($child))."(\$dbObject);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn \$this->_".strtolower($child)."List;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetChildrenFunction($child)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Makes this the parent of all $child objects in the $child List array. Any existing $child will become orphan(s)",'',"null");
		$this->string .= "\tfunction Set".ucfirst(strtolower($child))."List(&\$list)\n\t{";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List = array();";
		$this->string .= "\n\t\t\$this->Get".ucfirst(strtolower($child))."List();";
		$this->string .= "\n\t\tforeach (\$this->_".strtolower($child)."List as \$".strtolower($child).")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$".strtolower($child)."->".strtolower($this->objectName)."Id = '';";
		$this->string .= "\n\t\t\t\$".strtolower($child)."->Save(false);";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\t\$this->_".strtolower($child)."List = \$list;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"");
		$this->string .= "\tfunction Set".ucfirst(strtolower($parent))."(&\$".strtolower($parent).")\n\t{";
		$this->string .= "\n\t\t\$this->".strtolower($parent)."Id = $".strtolower($parent)."->".strtolower($parent)."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateGetParentFunction($parent)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $parent object to this one",'',"boolean");
		$this->string .= "\tfunction Get".ucfirst(strtolower($parent))."()\n\t{";
		$this->string .= "\n\t\t\$".strtolower($parent)." = new ".$parent."();";
		$this->string .= "\n\t\treturn $".strtolower($parent)."->Get(\$this->".strtolower($parent)."Id);";
		$this->string .= "\n\t}";
	}


	// Relations {Many-Many} functions

	// -------------------------------------------------------------
	function CreateAddAssociationFunction($sibling)
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Associates the $sibling object to this one",'',"");
		$this->string .= "\tfunction Add".ucfirst(strtolower($sibling))."(&\$".strtolower($sibling).")\n\t{";
		$this->string .= "\n\t\tif (\$".strtolower($sibling)." instanceof ".$sibling.")";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tif (in_array(\$this, \$".strtolower($sibling)."->".strtolower($this->objectName)."List, true))";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\treturn false;";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\telse";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$found = false;";
		$this->string .= "\n\t\t\t\tforeach (\$this->_".strtolower($sibling)."List as \$".strtolower($sibling)."2)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (\$".strtolower($sibling)."->".strtolower($sibling)."Id > 0 && \$".strtolower($sibling)."->".strtolower($sibling)."Id == \$".strtolower($sibling)."2->".strtolower($sibling)."Id)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$found = true;";
		$this->string .= "\n\t\t\t\t\t\tbreak;";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (!\$found)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->_".strtolower($sibling)."List[] = \$".strtolower($sibling).";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
	}

	//-------------------------------------------------------------
	function CreateGetAssociationsFunction($sibling)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Returns a sorted array of objects that match given conditions",array("multidimensional array {(\"field\", \"comparator\", \"value\"), (\"field\", \"comparator\", \"value\"), ...}","string \$sortBy","boolean \$ascending","int limit"),"array \$".strtolower($this->objectName)."List");
		$this->string .= "\tfunction Get".ucfirst(strtolower($sibling))."List(\$fcv_array = array(), \$sortBy='', \$ascending=true, \$limit='')\n\t{";
		$this->string .= "\n\t\t\$sqlLimit = (\$limit != '' && \$sortBy == ''?\"LIMIT \$limit\":'');";
		$this->string .= "\n\t\ttry";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$Database = new PDO(\$GLOBALS['configuration']['pdoDriver'].':host='.\$GLOBALS['configuration']['host'].';port='.\$GLOBALS['configuration']['port'].';dbname='.\$GLOBALS['configuration']['db'], \$GLOBALS['configuration']['user'], \$GLOBALS['configuration']['pass']);";
		$this->string .= "\n\t\t\t\$".strtolower($sibling)." = new ".$sibling."();";
		$this->string .= "\n\t\t\t\$".strtolower($sibling)."List = Array();";
		$this->string .= "\n\t\t\t\$this->pog_query = \"select distinct(a.".strtolower($sibling)."id) from ".strtolower($sibling)." a INNER JOIN ".strtolower($misc->MappingName($this->objectName, $sibling))." m ON m.".strtolower($sibling)."id = a.".strtolower($sibling)."id where m.".strtolower($this->objectName)."id = '\$this->".strtolower($this->objectName)."Id' \";";
		$this->string .= "\n\t\t\tif (sizeof(\$fcv_array) > 0)";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query .= \" AND \";";
		$this->string .= "\n\t\t\t\tfor (\$i=0, \$c=sizeof(\$fcv_array)-1; \$i<\$c; \$i++)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\tif (isset(\$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])]) && \$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'NUMERIC' && \$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'SET')";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$Database->Escape(\$fcv_array[\$i][2]).\"' AND\";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\t\$this->pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"' AND\";";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (isset(\$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])]) && \$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'NUMERIC' && \$".strtolower($sibling)."->pog_attribute_type[strtolower(\$fcv_array[\$i][0])][0] != 'SET')";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$Database->Escape(\$fcv_array[\$i][2]).\"'\";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$this->pog_query .= \"\".\$fcv_array[\$i][0].\" \".\$fcv_array[\$i][1].\" '\".\$fcv_array[\$i][2].\"'\";";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\t\$this->pog_query .= \" order by m.".strtolower($sibling)."id asc \$sqlLimit\";";
		$this->string .= "\n\t\t\t\$Database->Query(\$this->pog_query);";
		$this->string .= "\n\t\t\tforeach (\$Database->query(\$this->pog_query) as \$row)";
		$this->string .= "\n\t\t\t{";
     	$this->string .= "\n\t\t\t\t\$".strtolower($sibling)." = new ".$sibling."();";
     	$this->string .= "\n\t\t\t\t\$".strtolower($sibling)."->Get(\$row['".strtolower($sibling)."id']);";
		$this->string .= "\n\t\t\t\t\$".strtolower($sibling)."List[] = $".strtolower($sibling).";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tif (\$sortBy != '')";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$f = '';";
		$this->string .= "\n\t\t\t\tif (isset(\$".strtolower($sibling)."->pog_attribute_type[strtolower(\$sortBy)]) && (\$".strtolower($sibling)."->pog_attribute_type[strtolower(\$sortBy)][0] == \"NUMERIC\" || \$".strtolower($sibling)."->pog_attribute_type[strtolower(\$sortBy)][0] == \"SET\"))";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$f = 'return \$".strtolower($sibling)."1->'.\$sortBy.' > \$".strtolower($sibling)."2->'.\$sortBy.';';";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\telse if (isset(\$".strtolower($sibling)."->pog_attribute_type[strtolower(\$sortBy)]))";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$f = 'return strcmp(strtolower(\$".strtolower($sibling)."1->'.\$sortBy.'), strtolower(\$".strtolower($sibling)."2->'.\$sortBy.'));';";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tusort(\$".strtolower($sibling)."List, create_function('\$".strtolower($sibling)."1, \$".strtolower($sibling)."2', \$f));";
		$this->string .= "\n\t\t\t\tif (!\$ascending)";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$".strtolower($sibling)."List = array_reverse(\$".strtolower($sibling)."List);";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t\tif (\$limit != '')";
		$this->string .= "\n\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\$limitParts = explode(',', \$limit);";
		$this->string .= "\n\t\t\t\t\tif (sizeof(\$limitParts) > 1)";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\treturn array_slice(\$".strtolower($sibling)."List, \$limitParts[0], \$limitParts[1]);";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t\telse";
		$this->string .= "\n\t\t\t\t\t{";
		$this->string .= "\n\t\t\t\t\t\treturn array_slice(\$".strtolower($sibling)."List, 0, \$limit);";
		$this->string .= "\n\t\t\t\t\t}";
		$this->string .= "\n\t\t\t\t}";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\tforeach (\$".strtolower($sibling)."List as \$".strtolower($sibling).")";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->Add".ucfirst(strtolower($sibling))."(\$".strtolower($sibling).");";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t\treturn \$this->_".strtolower($sibling)."List;";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\tcatch(PDOException \$e)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\tthrow new Exception(\$e->getMessage());";;
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn null;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSetAssociationsFunction($sibling)
	{
		$misc = new Misc(array());
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Creates mappings between this and all objects in the $sibling List array. Any existing mapping will become orphan(s)",'',"null");
		$this->string .= "\tfunction Set".ucfirst(strtolower($sibling))."List(&\$".strtolower($sibling)."List)\n\t{";
		$this->string .= "\n\t\t\$map = new ".$misc->MappingName($this->objectName, $sibling)."();";
		$this->string .= "\n\t\t\$map->RemoveMapping(\$this);";
		$this->string .= "\n\t\t\$this->_".strtolower($sibling)."List = \$".strtolower($sibling)."List;";
		$this->string .= "\n\t}";
	}
}
?>
