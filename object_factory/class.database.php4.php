<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version &versionNumber&revisionNumber / &language
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	var $connection;

	function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		$this->connection = mysql_connect ($serverName.":".$databasePort, $databaseUser, $databasePassword) or die('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		mysql_select_db ($databaseName) or die ('I cannot find the specified database "'.$databaseName.'". Please edit configuration.php.');
	}

	function Connect()
	{
		static $database = null;
		if (!isset($database))
		{
			$database = new Database();
		}
		return $database->connection;
	}

	function Reader($query, $connection)
	{
		$cursor = mysql_query($query, $connection);
		return $cursor;
	}

	function Read($cursor)
	{
		return mysql_fetch_assoc($cursor);
	}

	function NonQuery($query, $connection)
	{
		mysql_query($query, $connection);
		$result = mysql_affected_rows($connection);
		if ($result == -1)
		{
			return false;
		}
		return $result;

	}

	function Query($query, $connection)
	{
		$result = mysql_query($query, $connection);
		return mysql_num_rows($result);
	}

	function InsertOrUpdate($query, $connection)
	{
		$result = mysql_query($query, $connection);
		return intval(mysql_insert_id($connection));
	}
}
?>
