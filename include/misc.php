<?php
	// -------------------------------------------------------------
	function IsPostback()
	{
		if (count($_POST) > 0)
		{
			return true;
		}
		return false;
	}

	// -------------------------------------------------------------
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
?>