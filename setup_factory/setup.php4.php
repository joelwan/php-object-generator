<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
*
* This setup file does the following:
* 1. Checks if configuration file is present
* 2. Checks if the data in the configuration file is correct
* 3. Checks if the database and table exist
* 4. Create table if not present
* 5. Tests 5 CRUD functions and determine if everything is OK for all objects within the current directory
* 6. When all tests pass, provides an interface to the database and a way to manage objects.
*/
if(file_exists("../configuration.php"))
{
	include_once("../configuration.php");
}
else
{
	echo "configuration file missing<br/>";
	exit;
}
include_once("setup_library/authentication.php");
include_once("setup_library/setup_misc.php");
if(!isset($_SESSION['diagnosticsSuccessful']) || (isset($_GET['step']) && $_GET['step']=="diagnostics"))
{
	$_SESSION['diagnosticsSuccessful'] = false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Php Object Generator Setup <?php echo $GLOBALS['configuration']['versionNumber'].$GLOBALS['configuration']['revisionNumber']?></title>
<link rel="stylesheet" href="./setup.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="./setup_library/xPandMenu.css"/>
<script src="./setup_library/xPandMenu.js"></script>
</head>
<body>
<div class="header">
<?php include "setup_library/inc.header.php";?>
</div>
<form action="./index.php" method="POST">
<?php
ini_set("error_reporting", 0);
if(count($_POST) > 0 && $_SESSION['diagnosticsSuccessful']==false)
{
?>
<div class="container">
<div class="left">
	<div class="logo2"></div>
	<div class="text"><div class="gold">POG setup diagnostics</div>
	<br/>Setup performs unit tests on all your objects in the object directory and makes sure they're OK. <br/>This makes sure that your objects can talk to your database correctly. This can also be useful if you modify / customize the objects manually and want to make sure they still work once you're done.
	<br/><br/>The diagnostics screen on the right shows the results of those tests.
	</div>
</div>
<div class="middle">
	<div id="tabs">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults_on.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_manageobjects.gif"/>
	</div><div class="subtabs">&nbsp;</div><a href="./index.php?step=diagnostics"><img src="./setup_images/setup_recheck.jpg" border="0"/></a><div class="middle2">
<?php
	//perform diagnostics
	if (isset($GLOBALS['configuration']['pdoDriver']))
	{

	}
	else
	{
		if(file_exists("../objects/class.database.php"))
		{
			include "../objects/class.database.php";
			//try connecting to the database

			$database = new DatabaseConnection();
			//success
			//scan for generated objects.
			$dir = opendir('../objects/');
			$objects = array();
			while(($file = readdir($dir)) !== false)
			{
				if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.php' && !is_dir($file) && $file != "class.database.php" && $file != "configuration.php" && $file != "setup.php")
				{
					$objects[] = $file;
				}
			}
			closedir($dir);
			$objectNameList = array();
			$errors = 0;
			$diagnostics = "";
			$_SESSION['links'] = array();
			foreach ($objects as $object)
			{
				include("../objects/{$object}");
			}
			foreach($objects as $object)
			{
				$content = file_get_contents("../objects/".$object);
				$contentParts = split("<b>",$content);
				if (isset($contentParts[1]))
				{
					$contentParts2 = split("</b>",$contentParts[1]);
				}
				if (isset($contentParts2[0]))
				{
					$className = trim($contentParts2[0]);
				}
				if (isset($className))
				{
					$diagnostics .= "TESTING $className...\n";
					$objectNameList[] = $className;

					//get sql
					$sqlParts = split(";",$contentParts[0]);
					$sqlPart = split("CREATE",$sqlParts[0]);
					$sql = "CREATE ".$sqlPart[1].";";

					$linkParts1 = split("\*\/", $contentParts[1]);
					$linkParts2 = split("\@link", $linkParts1[0]);
					$link = $linkParts2[1];


					eval('$instance = new '.$className.'();');

					$attributeList = array_keys(get_object_vars($instance));
					$type_value = InitializeTestValues($instance->pog_attribute_type);

  					foreach($attributeList as $attribute)
					{
						if (isset($instance->pog_attribute_type[strtolower($attribute)]))
						{
  							if (isset($type_value[strtolower($attribute)]))
  							{
 								$instance->{$attribute} = $type_value[strtolower($attribute)];
  							}
  							else if ($instance->pog_attribute_type[strtolower($attribute)][0] != "OBJECT")
  							{
  								$instance->{$attribute} = "1";
  							}
						}
					}
  					//Test Save()
  					$instanceId = false;
  					$instance->{strtolower($className)."Id"} = 0;
					$instanceId = $instance->Save(false);
  					if(!$instanceId)
  					{
  						//table doesn't exist
						//try to create table
						$database = new DatabaseConnection();
						$database->Query($sql);
						$instanceId = $instance->Save(false);
      					if(!$instanceId)
      					{
      						$diagnostics .= "Could not create table.";
      						$diagnostics .= "ERROR: Save() could not be performed\n";
      						$diagnostics .= $instance->pog_query."\n";
      						$errors++;
      					}
      					else
      					{
      						$diagnostics .= "Created Table $className successfully\n";
      						$diagnostics .= "Testing Save()....OK\n";
      					}
  					}
  					else
  					{
  						$diagnostics .=  "Testing Save()....OK\n";
  					}

  					//Test SaveNew()
  					if(!$instance->SaveNew(false))
  					{
  						$diagnostics .= "ERROR: SaveNew() could not be performed\n";
  						$diagnostics .= $instance->pog_query."\n";
  						$errors++;
  					}
  					else
  					{
  						$instance->SaveNew(false);
  						$diagnostics .= "Testing SaveNew()....OK\n";
  					}

  					//Test GetList();
  					//GetList() implicitly tests Get()
  					$instanceList = $instance->GetList(array(array(strtolower($className)."Id",">",0)));
  					if($instanceList == null)
  					{
  						$diagnostics .= "ERROR: GetList() could not be performed\n";
  						$diagnostics .= $instance->pog_query."\n";
  						$errors++;
  					}
  					else
  					{
  						$diagnostics .= "Testing Get()....OK\n";
  						$diagnostics .= "Testing GetList()....\n";
  						$oldCount = count($instanceList);
  						//Test Multiple Conditions
  						$instanceList = $instance->GetList(array(array(strtolower($className)."Id", ">=",$instanceId), array(strtolower($className)."Id", "<=", $instanceId+2)), strtolower($className)."Id", false, 2);
  						$diagnostics .= "\tTesting Limit....";
  						if (sizeof($instanceList) != 2)
  						{
  							//Test Limit
  							$diagnostics .= "ERROR: GetList() :sizeof(list) != \$limit\n";
	  						$diagnostics .= $instance->pog_query."\n";
	  						$errors++;
  						}
  						else
  						{
  							$diagnostics .= "OK\n";
  						}
  						$diagnostics .= "\tTesting Sorting....";
  						if ($instanceList[1]->{strtolower($className)."Id"} > $instanceList[0]->{strtolower($className)."Id"})
  						{
  							//Test Sorting
  							$diagnostics .= "ERROR: GetList() :list is not properly sorted\n";
	  						$diagnostics .= $instance->pog_query."\n";
	  						$errors++;
  						}
  						else
  						{
  							$diagnostics .= "OK\n";
  						}
  						if ($errors == 0)
  						{
  							$diagnostics .= "Testing GetList()....OK\n";
  							$instanceList = $instance->GetList(array(array(strtolower($className)."Id", ">=",$instanceId), array(strtolower($className)."Id", "<=", $instanceId+2)), strtolower($className)."Id", false, 3);
	  						foreach ($instanceList as $instance)
	  						{
	  							$attributeList = array_keys(get_object_vars($instance));
	  							foreach ($attributeList as $attribute)
	  							{
			      					if (isset($instance->pog_attribute_type[strtolower($attribute)]))
		  							{
			  							if (isset($type_value[strtolower($attribute)]))
			  							{
		      								if ($instance->{$attribute} != $type_value[strtolower($attribute)])
		      								{
		      									$diagnostics .= "WARNING: Failed to retrieve attribute `$attribute`. Expecting `".$type_value[strtolower($attribute)]."`; found `".$instance->{$attribute}."`. Check that column `$attribute` in the `$className` table is of type `".$instance->pog_attribute_type[strtolower($attribute)][1]."`\n";
		      								}
			  							}
		  							}
	  							}
  								$instance->Delete();
	  						}
  						}
  						else
  						{
  							$diagnostics .= "Testing GetList()....Failed\n";
  						}
  						$instanceList = $instance->GetList(array(array(strtolower($className)."Id",">",0)));
  						if ($instanceList == null)
  						{
  							$instanceList = array();
  						}
  						$newCount = count($instanceList);
  						if($oldCount-3 == $newCount)
  						{
  							$diagnostics .= "Testing Delete()....OK\n";
  						}
  						else
  						{
  							$diagnostics .= "ERROR: Delete() could not be performed\n";
  							$diagnostics .= $instance->pog_query."\n";
  							$errors++;
  						}
  					}
  					if ($errors == 0)
					{
						$database->Query("optimize table ".strtolower($className));
						$diagnostics .= "Optimizing ".$className."....OK\n-----\n";
						$_SESSION['links'][$className] = $link;
					}
					$contentParts2 = null;
					$className = null;
				}
			}
			$diagnostics .= "\nFOUND & CHECKED ".count($objectNameList)." OBJECT(S)\n";
			$_SESSION['fileNames'] = serialize($objects);
			$_SESSION['objectNameList'] = serialize($objectNameList);
			echo "<textarea>$diagnostics</textarea></div>";
			if ($errors == 0)
			{
				$_SESSION['diagnosticsSuccessful'] = true;
				echo '<input type="image" src="./setup_images/setup_proceed.gif" name="submit"/>';
			}
			else
			{
				$diagnostics .= "FOUND $errors ERROR(S)\n";
				//echo "<input type='submit' name='submit' value='Retry'/>";
			}
		}
		else
		{
			echo "database wrapper (class.database.php) missing<br/>";
		}
	}
$_POST = null;
$instanceId = null;
?>
</div></div>
<?php
}
else if($_SESSION['diagnosticsSuccessful'] == true)
{
?>
<div class="container">
	<div class="left">
		<div class="logo3"></div>
		<div class="text"><div class="gold">POG documentation summary</div>
		<br/><br/>The following 3 documents summarize what POG is all about:<br/><br/>
		1. <a href="http://www.phpobjectgenerator.com/plog/file_download/15">POG Essentials</a><br/><br/>
		2. <a href="http://www.phpobjectgenerator.com/plog/file_download/21">POG Object Relations</a><br/><br/>
		3. <a href="http://www.phpobjectgenerator.com/plog/file_download/18">POG SOAP API</a>
		</div><!--text-->
	</div><!--left-->
<div class="middle33">
	<div id="tabs3">
		<a href="./index.php?step=diagnostics"><img src="./setup_images/tab_setup.gif"/></a>
		<img src="./setup_images/tab_separator.gif"/>
		<img src="./setup_images/tab_diagnosticresults.gif"/>
		<img src="./setup_images/tab_separator.gif"/>
		<a href="./index.php"><img src="./setup_images/tab_manageobjects_on.gif"/></a>
	</div><!--tabs3--><div class="subtabs">
<?php
	//provide interface to the database
	include "./setup_library/xPandMenu.php";
	$root = new XMenu();
	if(file_exists("configuration.php"))
	{
		include "../configuration.php";
	}
	if(file_exists("../objects/class.database.php"))
	{
		include "../objects/class.database.php";
	}

	$fileNames = unserialize($_SESSION['fileNames']);
	foreach($fileNames as $filename)
	{
		include("../objects/{$filename}");
	}
	$objectNameList = unserialize($_SESSION['objectNameList']);
	if (isset($_GET['objectName']))
	{
		$_SESSION['objectName'] = $_GET['objectName'];
	}
	$objectName = (isset($_SESSION['objectName'])?$_SESSION['objectName']:$objectNameList[0]);

	?>
	<div id="header">
  	<ul>
  	<li id='inactive'>My Tables:</li>
	<?php
	if (!isset($_SESSION['objectName']))
	{
		$_SESSION['objectName'] = $objectNameList[0];
	}
	for($i=0; $i<count($objectNameList); $i++)
	{
		echo "<li ".($_SESSION['objectName']==$objectNameList[$i]?"id='current'":'')."><a href='./index.php?objectName=".$objectNameList[$i]."'>".$objectNameList[$i]."</a></li>";
		//echo "<a href='./index.php?objectName=".$objectNameList[$i]."'".(isset($_SESSION['objectName']) && $_SESSION['objectName']==$objectNameList[$i]?"class='activetab'":(!isset($_SESSION['objectName'])&&$i==0?"class='activetab'":"inactivetab")).">".$objectNameList[$i]."</a> ";
	}
	$connection = Database::Connect();
	$count = 0;
	$sql = 'show index from `'.strtolower($_SESSION['objectName']).'` where Key_name = "searching"';
	$cursor = Database::Reader($sql,$connection);
	while ($row = Database::Read($cursor))
	{
		$count++;
	}
	?>
	</ul>
	</div><!--header-->
	</div><!--subtabs-->
	<div class="toolbar"><div style="float:left;"><a href="<?php echo $_SESSION['links'][$_SESSION['objectName']]?>" target="_blank" title="modify and regenerate object"><img src="./setup_images/setup_regenerate.jpg" border="0"/></a><a href="#" title="Delete all objects" onclick="if (confirm('Are you sure you want to delete all objects in this table? TPress OK to Delete.')){window.location='./?thrashall=true';}else{alert('Phew, nothing was deleted ;)');}"><img src='./setup_images/setup_deleteall.jpg' alt='delete all' border="0"/></a><a href="#" onclick="javascript:expandAll();return false;" title="expand all nodes"><img src='./setup_images/setup_expandall.jpg' alt='expand all' border="0"/></a><a href="#" onclick="javascript:collapseAll();return false;" title="collapse all nodes"><img src='./setup_images/setup_collapseall.jpg' alt='collapse all' border="0"/></a><a href="#" title="update all objects to newest POG version" onclick="if (confirm('Setup will now attempt to upgrade your objects by contacting the POG SOAP server. Would you like to continue?')){window.location='./setup_library/upgrade.php';}else{alert('Upgrade aborted');}"><img src='./setup_images/setup_updateall.jpg' alt='update all objects' border='0'/></a></div><?php if ($count>0){?><div style="position:relative;float:left;height:20px;padding-top:10px;padding-left:15px;width:100px;"><input id='search_objects' type="text" name="Search" value="Search <?php echo ucfirst($_SESSION['objectName'])?>" style="color:#666;font-size:9px;" /></div><?php } ?></div><div class="middle3">
	<?php
	//is there an action to perform?
	if (isset($_GET['thrashall']))
	{
		eval('$instance = new '.$objectName.'();');
		$instanceId = strtolower(get_class($instance))."Id";
		$instanceList = $instance->GetList(array(array($instanceId, ">", "0")));
		foreach ($instanceList as $instance)
		{
			$instance->Delete();
		}
		$_GET = null;
	}
	echo "<input id='hidden_object_name' type='hidden' value='".$objectName."'></input>";
	echo "<script>sndReq('GetList', '', '$objectName', '', '', '', '$objectName');</script>";
	echo '<div id="container"></div>';
	$_SESSION['fileNames'] = serialize($fileNames);
	$_SESSION['objectNameList'] = serialize($objectNameList);
	$_SESSION['pluginNameList'] = serialize($pluginNameList);
?>
<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
</div><!--middle3-->
</div><!--middle33-->
</div><!--container-->
<?php
}
else
{
	//welcome screen
?>
<div class="container">
	<div class="left">
		<div class="logo"></div>
		<div class="text"><div class="gold">What is POG Setup?</div>POG Setup is an extension of the online Php Object Generator. It is meant to help the veteran POG user and the novice alike.
		<br/><br/>POG Setup is a 3 step process which:<br/><br/>
		1. Creates tables for your generated objects.<br/><br/>
		2. Performs diagnostics tests on all objects within your 'objects' directory.<br/><br/>
		3. Provides a light interface to your object tables.</div>
	</div>
	<div class="middle">
		<div id="tabs">
			<img src="./setup_images/tab_setup_on.gif" height="20px" width="70px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_diagnosticresults.gif" height="20px" width="137px"/>
			<img src="./setup_images/tab_separator.gif" height="20px" width="17px"/>
			<img src="./setup_images/tab_manageobjects.gif" height="20px" width="129px"/>
		</div>
		<div id="nifty">
			<div style="height:500px">
			<img src="./setup_images/setup_welcome.jpg" height="47px" width="617px"/>
			<div class="col1"><img src="./setup_images/pog_setup_closed.jpg"/><div class="gold">What is POG?</div>POG generates PHP objects with integrated CRUD methods to dramatically accelerate web application development in PHP. <br/>
			<br/>POG allows developers to easily map object attributes onto columns of a database table without having to write SQL queries.</div>
			<div class="col2"><img src="./setup_images/pog_setup_open.jpg"/><div class="gold">What is POG Setup?</div>You've generated one or more objects using Php Object Generator ... Now what?<br/>
			<br/>POG SETUP is an answer to this question and takes the POG experience one step further. The Setup process automates <b>table creation</b>, <b>unit testing</b> and provides a light <b>scaffolding</b> environment.</div>
			<div class="col3">
			<div class="gold">If you are ready to get POG'd up, click on thebutton below to proceed. Doing this will:</div>
			<br/>1. Establish a database connection.<br/>
			2. Create table(s) for your objec(s), if required.<br/>
			3. Perform diagnostics tests on your object(s).<br/>
			4. Provide you with the test results.<br/><input type="image" src="./setup_images/setup_pogmeup.gif" name="submit"/></div>
			</div>
			<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
		</div>
	</div>
</div>
<?php
}
?>
</form>
<div class="footer">
<?php include "setup_library/inc.footer.php";?>
</div>
</body>
</html>