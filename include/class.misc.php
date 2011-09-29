<?php
class Misc
{
	var $string;
	var $objectList = Array();
	var $attributeList;
	var $optionList;
	var $separator = "\n\t// -------------------------------------------------------------";

	// -------------------------------------------------------------
	function Misc($objectList, $attributeList = '', $optionList ='')
	{
		$this->objectList = $objectList;
		$this->attributeList = $attributeList;
		$this->optionList = $optionList;
	}

	/**
	 * Used by POG web interface to render attribute as either drop down list or text field
	 *
	 * @param string $type
	 * @return bool
	 */
	function TypeIsKnown($type)
	{
		if ($type=="VARCHAR(255)"	//mysql
		|| $type=="TINYINT"
		|| $type=="TEXT"
		|| $type=="INT"
		|| $type=="DATE"
		|| $type=="SMALLINT"
		|| $type=="MEDIUMINT"
		|| $type=="BIGINT"
		|| $type=="FLOAT"
		|| $type=="DOUBLE"
		|| $type=="DECIMAL"
		|| $type=="DATETIME"
		|| $type=="TIMESTAMP"
		|| $type=="TIME"
		|| $type=="YEAR"
		|| $type=="CHAR(255)"
		|| $type=="TINYBLOB"
		|| $type=="TINYTEXT"
		|| $type=="BLOB"	//firebird
		|| $type=="MEDIUMBLOB"
		|| $type=="MEDIUMTEXT"
		|| $type=="LONGBLOB"
		|| $type=="LONGTEXT"
		|| $type=="BINARY"
		|| $type=="BLOB"
		|| $type=="CHAR"
		|| $type=="CHAR(1)"
		|| $type=="INT64"
		|| $type=="INTEGER"
		|| $type=="NUMERIC"
		|| $type=="BIGSERIAL"	//postgresql
		|| $type=="BIT"
		|| $type=="BOOLEAN"
		|| $type=="BOX"
		|| $type=="BYTEA"
		|| $type=="CIRCLE"
		|| $type=="DOUBLE PRECISION"
		|| $type=="INET"
		|| $type=="LINE"
		|| $type=="LSEG"
		|| $type=="MACADDR"
		|| $type=="MONEY"
		|| $type=="OID"
		|| $type=="PATH"
		|| $type=="POINT"
		|| $type=="REAL"
		|| $type=="SERIAL"
		|| $type=="MONEY"
		|| $type=="IMAGE"	//odbc
		|| $type=="NCHAR"
		|| $type=="NTEXT"
		|| $type=="NVARCHAR"
		|| $type=="SMALLDATETIME"
		|| $type=="SMALLINT"
		|| $type=="SMALLMONEY"
		|| $type=="UNIQUEIDENTIFIER"
		|| $type=="VARBINARY"
		|| $type=="HASMANY"
		|| $type=="BELONGSTO"
		|| $type=="JOIN"
		)
		return true;
		else
		return false;
	}

	/**
	 * Gets specified variable from 3 main variable spaces, in order of precedence: $_GET, $_POST and $_SESSION)
	 *
	 * @param string $variableName
	 * @return mixed
	 */
	function GetVariable($variableName)
	{
		if (isset($_GET[$variableName]))
		{
			return $_GET[$variableName];
		}
		if (isset($_POST[$variableName]))
		{
			return $_POST[$variableName];
		}
		if (isset($_SESSION[$variableName]))
		{
			return $_SESSION[$variableName];
		}
		return null;
	}

	/**
	 * Determines whether or not SQL datatype is numeric
	 *
	 * @param string $type
	 * @return bool
	 */
	function TypeIsNumeric($type)
	{
		$attributeTypeParts = explode("(",$type);
		$type = strtoupper(trim($attributeTypeParts[0]));
		if ($type=="TINYINT"	//mysql
		|| $type=="INT"
		|| $type=="DATE"
		|| $type=="SMALLINT"
		|| $type=="MEDIUMINT"
		|| $type=="BIGINT"
		|| $type=="FLOAT"
		|| $type=="DOUBLE"
		|| $type=="DECIMAL"
		|| $type=="TIMESTAMP"
		|| $type=="TIME"
		|| $type=="YEAR"
		|| $type=="INT64"
		|| $type=="INTEGER"
		|| $type=="NUMERIC"
		|| $type=="BIGSERIAL"	//postgresql
		|| $type=="DOUBLE PRECISION"
		|| $type=="MONEY"
		|| $type=="OID"
		|| $type=="REAL"
		|| $type=="SERIAL"
		|| $type=="MONEY"
		|| $type=="SMALLINT"
		|| $type=="SMALLMONEY"
		|| $type=="UNIQUEIDENTIFIER"
		)
		return true;
		else
		return false;
	}

	/**
	 * Determines whether or not SQL datatype is a set
	 *
	 * @param string $type
	 * @return bool
	 */
	function TypeIsSet($type)
	{
		$attributeTypeParts = explode("(",$type);
		$type = strtoupper(trim($attributeTypeParts[0]));
		if ($type=="ENUM"	//mysql
		|| $type=="SET"
		)
		return true;
		else
		return false;
	}

	/**
	 * Gets the SQL type
	 * These types are used by POG Setup (unit tests)
	 * For example:
	 * VARCHAR(255) is recognized as VARCHAR
	 * DECIMAL(12,2) is recognized as DECIMAL
	 *
	 * @param string $type
	 * @return string
	 */
	function GetAttributeType($type)
	{
		$attributeTypeParts = explode("(",$type);
		return $type = strtoupper(trim($attributeTypeParts[0]));
	}

	/**
	 * Transforms SQL type into POG's understanding of types
	 * The interpreted types are used by:
	 * GetList() (sorting)
	 * Todo: Escape and Unescape should take these into account to escape more intelligently
	 *
	 * Type Rules:
	 * NUMERIC - sorting performed by comparing values numerically
	 * TEXT - sorting performed by comparing values textually
	 * SET - sorting performed by comparing values textually
	 *
	 * @param string $type
	 * @return string
	 */
	function InterpretType($type)
	{
		if ($this->TypeIsNumeric($type))
		{
			return "NUMERIC";
		}
		else if ($this->TypeIsSet($type))
		{
			return "SET";
		}
		else if ($type == "HASMANY" || $type == "BELONGSTO" || $type == "JOIN")
		{
			return "OBJECT";
		}
		else
		{
			return "TEXT";
		}
	}

	/**
	 * Interprets and returns the length limit on a particular attribute.
	 * The interpreted lengths are used by:
	 * POG Setup (unit testing)
	 * Todo: Save() could use this for validation
	 *
	 * @param unknown_type $type
	 * @return unknown
	 */
	function InterpretLength($type)
	{
		$typeParts = explode('(', $type);
		if (count($typeParts) > 1)
		{
			$typeParts = explode(')', $typeParts[1]);
			if (strpos($typeParts[0], ')') === false && stripcslashes(trim($typeParts[0])) != '')
			{
				return stripcslashes(trim($typeParts[0]));
			}
			else
			{
				return null;
			}
		}

	}

	/**
	 * Creates the mapping name
	 *
	 * @param unknown_type $objectName1
	 * @param unknown_type $objectName2
	 * @return unknown
	 */
	function MappingName($objectName1, $objectName2)
	{
		$array = array($objectName1, $objectName2);
		sort($array);
		return implode($array)."Map";
	}
}
?>