<?php
class ObjectMap
{
 	var $string;
	var $sql;

	var $object1;
	var $object2;

	var $separator = "\n\t";

	// -------------------------------------------------------------
	function ObjectMap($object1, $object2)
	{
		$this->object1 = $object1;
		$this->object2 = $object2;
	}

	// -------------------------------------------------------------
	function BeginObject()
	{
		$this->string = "<?php\n";
		$this->string .= $this->CreatePreface();
		$this->string .= "\nclass ".$this->object1.$this->object2."Map\n{\n\t";
		$this->string .= "var \$".strtolower($this->object1)."Id = '';\n\n\t";
		$this->string .= "var \$".strtolower($this->object2)."Id = '';\n\n\t";
		//	create attribute => type array map
		//	needed for setup
		$this->string .= "var \$pog_attribute_type = array(\n\t\t";
		$this->string .= "\"".strtolower($this->object1)."Id\" => array('db_attributes' => array(\"NUMERIC\", \"INT\")),\n\t\t";
		$this->string .= "\"".strtolower($this->object2)."Id\" => array('db_attributes' => array(\"NUMERIC\", \"INT\")));\n\t\t";
		$this->string .= "var \$pog_query;";
	}

	// -------------------------------------------------------------
	function EndObject()
	{
		$this->string .= "\n}\n?>";
	}

	// -------------------------------------------------------------
	function CreateConstructor()
	{
		$this->string .= "\n\t\n\tfunction ".$this->object1.$this->object2."Map(\$".$this->object1."Id = '', \$".strtolower($this->object2)."Id = '')";
		$this->string .= "\n\t\t\$this->".strtolower($this->object1)."Id = \$".$this->object1."Id;";
		$this->string .= "\n\t\t\$this->".strtolower($this->object2)."Id = \$".$this->object2."Id;";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateSQLQuery()
	{
		$this->sql .= "\tCREATE TABLE `".strtolower($this->object1).strtolower($this->object2)."map` (";
		$this->sql .= "\n\t`".strtolower($this->object1)."id` int(11) NOT NULL,";
		$this->sql .= "\n\t`".strtolower($this->object2)."id` int(11) NOT NULL,";
		$this->sql .= "INDEX(`".strtolower($this->object1)."id`, `".strtolower($this->object2)."id`)) ENGINE=MyISAM;";
	}

	// -------------------------------------------------------------
	function CreateSaveFunction()
	{
		$this->string .= "\n\t".$this->separator."\n\t";
		$this->string .= $this->CreateComments("Physically saves the mapping to the database",'', '');
		$this->string .= "\tfunction Save()\n\t{";
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\t\$this->pog_query = \"select `".strtolower($this->object1)."id` from `".strtolower($this->object1).strtolower($this->object2)."map` where `".strtolower($this->object1)."id`='\".\$this->".strtolower($this->object1)."Id.\"' AND `".strtolower($this->object2)."id`='\".\$this->".strtolower($this->object2)."Id.\"' LIMIT 1\";";
		$this->string .= "\n\t\t\$rows = Database::Query(\$this->pog_query, \$connection);";
		$this->string .= "\n\t\tif (\$rows == 0)";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query = \"insert into `".strtolower($this->object1).strtolower($this->object2)."map` (`".strtolower($this->object1)."id`, `".strtolower($this->object2)."id`) values ('\".\$this->".strtolower($this->object1)."Id.\"', '\".\$this->".strtolower($this->object2)."Id.\"')\";";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\treturn Database::InsertOrUpdate(\$this->pog_query, \$connection);";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateRemoveMappingFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Removes the mapping between the two objects", array("Object \$object", "Object \$object2"), "");
		$this->string .= "\tfunction RemoveMapping(\$object, \$otherObject = null)\n\t{";
		$this->string .= "\n\t\t\$connection = Database::Connect();";
		$this->string .= "\n\t\tif (is_a(\$object, \"".$this->object1."\"))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query = \"delete from `".strtolower($this->object1).strtolower($this->object2)."map` where `".strtolower($this->object1)."id` = '\".\$object->".strtolower($this->object1)."Id.\"'\";";
		$this->string .= "\n\t\t\tif (\$otherObject != null && is_a(\$otherObject, \"".$this->object2."\"))";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query .= \" and `".strtolower($this->object2)."id` = '\".\$otherObject->".strtolower($this->object2)."Id.\"'\";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse if (is_a(\$object, \"".$this->object2."\"))";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->pog_query = \"delete from `".strtolower($this->object1).strtolower($this->object2)."map` where `".strtolower($this->object2)."id` = '\".\$object->".strtolower($this->object2)."Id.\"'\";";
		$this->string .= "\n\t\t\tif (\$otherObject != null && is_a(\$otherObject, \"".$this->object1."\"))";
		$this->string .= "\n\t\t\t{";
		$this->string .= "\n\t\t\t\t\$this->pog_query .= \" and `".strtolower($this->object1)."id` = '\".\$otherObject->".strtolower($this->object1)."Id.\"'\";";
		$this->string .= "\n\t\t\t}";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\tDatabase::NonQuery(\$this->pog_query, \$connection);";
		$this->string .= "\n\t}";
	}

	// -------------------------------------------------------------
	function CreateAddMappingFunction()
	{
		$this->string .= "\n\t$this->separator\n\t";
		$this->string .= $this->CreateComments("Creates a mapping between the two objects", array("$this->object1 \$object", "$this->object2 \$otherObject"),"");
		$this->string .= "\tfunction AddMapping(\$object, \$otherObject)\n\t{";
		$this->string .= "\n\t\tif (is_a(\$object, \"".$this->object1."\") && \$object->".strtolower($this->object1)."Id != '')";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->object1)."Id = \$object->".strtolower($this->object1)."Id;";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->object2)."Id = \$otherObject->".strtolower($this->object2)."Id;";
		$this->string .= "\n\t\t\treturn \$this->Save();";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse if (is_a(\$object, \"".$this->object2."\") && \$object->".strtolower($this->object2)."Id != '')";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->object2)."Id = \$object->".strtolower($this->object2)."Id;";
		$this->string .= "\n\t\t\t\$this->".strtolower($this->object1)."Id = \$otherObject->".strtolower($this->object1)."Id;";
		$this->string .= "\n\t\t\treturn \$this->Save();";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t\telse";
		$this->string .= "\n\t\t{";
		$this->string .= "\n\t\t\treturn false;";
		$this->string .= "\n\t\t}";
		$this->string .= "\n\t}";
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
		$this->string .= "\n* <b>".$this->object1.$this->object2."Map</b> class with integrated CRUD methods.";
		$this->string .= "\n* @author ".$GLOBALS['configuration']['author'];
		$this->string .= "\n* @version POG ".$GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber']." / PHP5";
		$this->string .= "\n* @copyright ".$GLOBALS['configuration']['copyright'];
		$this->string .= "\n*/";
	}
}
?>