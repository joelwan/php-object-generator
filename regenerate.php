<?php
session_start();
if (isset($_POST['atlink']))
{
	$url = $_POST['atlink'];

	//parse and put as post

	$link = explode('?', $url);

	$linkParts = explode('&', $link[1]);
	for ($i = 0; $i < sizeof($linkParts); $i++)
	{
		$arguments = split('=', $linkParts[$i]);
		$value = trim(stripcslashes(urldecode($arguments[1])));

		if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "attributeList")
		{
			eval ("$".$arguments[0]." = ".stripcslashes(urldecode($value)).";");
			$_SESSION['attributeList'] = serialize($attributeList);

		}
		else if (strlen($value) > 5 && substr(strtolower($value), 0, 5) == "array" && $arguments[0] == "typeList")
		{
			if (strpos(strtolower($value), "enum") == false && strpos(strtolower($value), "set") == false)
			{
				eval ("$".$arguments[0]." = ".urldecode($value).";");
			}
			else
			{
				$typeList = array();
				$value_parts = explode('=>', $value);

				for($j = 1; $j < sizeof($value_parts); $j++)
				{
					$value_part = $value_parts[$j];
					if (strpos(strtolower($value_part), "enum") != false)
					{
						$val = explode("(", $value_part);
						$val = explode(")", $val[1]);
						$typeList[] = "enum(".stripcslashes($val[0]).")";
					}
					else if (strpos(strtolower($value_part), "set") != false)
					{
						$val = explode("(", $value_part);
						$val = explode(")", $val[1]);
						$typeList[] = "set(".stripcslashes($val[0]).")";
					}
					else
					{
						$val = explode("'", $value_part);
						$typeList[] = $val[1];
					}
				}
			}
			$_SESSION['typeList'] = serialize($typeList);
		}
		else
		{
			eval ("\$_SESSION['".$arguments[0]."'] = '".$value."';");
		}
	}
	if (!isset($pdoDrive))
	{
		$pdoDrive = '';
	}

	header("Location:index.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Php Object Generator Setup</title>
<link rel="stylesheet" href="phpobjectgenerator.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="./setup_library/xPandMenu.css"/>
<div align="center">
<form action="./regenerate.php" method="POST"><br/>
<img src="setup_factory/setup_files/setup_images/mini_pog.jpg"/><br/><br/>
Paste @link below<br/><br/>
<input name="atlink" type="text" class="i" style="width:500px;"/>
<br/><br/><input type="image" src="setup_factory/setup_files/setup_images/generate.jpg" name="submit"/>
</form>
</div>
</html>