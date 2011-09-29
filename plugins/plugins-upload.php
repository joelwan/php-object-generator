<?php
session_start();
include "configuration.php";
include "objects/class.database.php";
include "objects/class.plugin.php";

$pending=false;

if(sizeof($_POST) > 0){
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['description'] = $_POST['description'];
	$_SESSION['author'] = $_POST['author'];
	$_SESSION['code'] = $_POST['code'];
	$_SESSION['php'] = $_POST['php'];
	$_SESSION['mysql'] = $_POST['mysql'];
}
if (sizeof($_POST) > 0 && $_POST['name'] != '' && $_POST['description'] != '' && $_POST['author'] != '' && $_POST['code'] != ''){
	$plugin = new plugin();
	$plugin->name = $_POST['name'];
	$plugin->description = $_POST['description'];
	$plugin->author = $_POST['author'];
	$plugin->code = base64_encode($_POST['code']);
	$plugin->active=0;
	$plugin->php=$_POST['php'];
	$plugin->mysql=$_POST['mysql'];
	$plugin->Save();
	$pending=true;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
<link rel="stylesheet" href="http://www.phpobjectgenerator.com/phpobjectgenerator.css" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" >
<title>Php Object Generator Plugins</title>
<meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="ICBM" content="53.5411, -113.4914">
<meta name="DC.title" content="PHP Object Generator (POG)">
<script src="http://www.phpobjectgenerator.com/pog.js" type="text/javascript">
</script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-72762-1";
urchinTracker();
</script>
</head>
<body>
<div class="main">
	<div class="left">
		POG Plugins provide additional methods to your POG objects. POG plugins are meant to encapsulate code
		that can be reused across different projects. As you move on to different projects over time,
		you can download, reuse or create your own plugins depending on your need for your current project.

		<br /><br />Thus, POG plugins allow developers to pick and choose additional functionality for their objects, without forcing them to
		download entire frameworks or libraries. This should keep your project moving fast, without sacrificing flexibility.
		<br /><br />


		<a href="./index.php">Browse plugins</a><br />
		<a href="./plugins-upload.php">Upload Plugin</a><br />
	</div><!-- left -->
	<div class="middle">
		<div style="width:300px;height:90px;float:left;padding-left:120px;">
			<div style="float:left;height:90px;background:url(plugins_splash.jpg) no-repeat;width:250px;"></div>
		</div>
		<div style="width:90%;height:430px;margin-top:10px;float:left;">
			<div style="float:left;width:100%;height:410px;font-weight:normal;background:#F7F7F7;padding:5px 0 0 5px;">
				<?php if($pending){ ?>

					<div style="font-weight:bold;width:300px;margin:150px 0 0 80px;">Thanks for sharing your plugin. It will go through a screening process and should appear in the plugins list shortly</div>
				<?php
					mail('joelwan@gmail.com','plugin uploaded','');
				}else{?>
				<span style="font-size:14px;font-weight:bold;">Upload your POG plugin</span><br /><br /><br />
				<form action="./plugins-upload.php" method="post">
				<table>
					<tr>
						<td align="right">Plugin Class Name:</td>
						<td width="10"></td>
						<td><input name="name" class="i" style="width:250px;" value="<?php echo @$_SESSION['name']?>"></input></td>
						<td class="error"><?php echo(sizeof($_POST) > 0 && empty($_SESSION['name'])?"Missing name":"")?></td>
						<td><!--<input type="button" style="width:100px;font-size:10px;" value="Check Availability"/>--></td>
					</tr>

					<tr>
						<td align="right">Author:</td>
						<td width="10"></td>
						<td><input class="i" style="width:250px;" name="author" value="<?php echo @$_SESSION['author']?>"></input></td>
						<td class="error"><?php echo(sizeof($_POST) > 0 && empty($_SESSION['author'])?"Missing author":"")?></td>
					</tr>
					<tr>
						<td align="right">PHP Compatibility:</td>
						<td width="10"></td>
						<td><select class="s" style="width:250px;height:20px;" name="php">
							<option value="PHP 4" <?php echo @($_SESSION['php']=="PHP 4" ? "selected" : "") ?>>PHP 4</option>
							<option value="PHP 5" <?php echo @($_SESSION['php']=="PHP 5" ? "selected" : "") ?>>PHP 5</option>
							<option value="PHP 5.1" <?php echo @($_SESSION['php']=="PHP 5.1" ? "selected" : "") ?>>PHP 5.1</option>
						</select>
						</td>
						<td class="error"></td>
					</tr>
					<tr>
						<td align="right">Database Compatibility:</td>
						<td width="10"></td>
						<td><select class="s" style="width:250px;height:20px;" name="mysql">
							<option value="MySQL 4" <?php echo @($_SESSION['mysql']=="MySQL 4" ? "selected" : "") ?>>MySQL 4</option>
							<option value="MySQL 5" <?php echo @($_SESSION['mysql']=="MySQL 5" ? "selected" : "") ?>>MySQL 5</option>
						</select>
						</td>
						<td class="error"></td>
					</tr>

					<tr>
						<td align="right">Description:</td>
						<td width="10"></td>
						<td><textarea class="t" name="description" style="width:250px;height:50px;" cols="10" rows="1"><?php echo @$_SESSION['description']?></textarea></td>
						<td class="error"><?php echo(sizeof($_POST) > 0 && empty($_SESSION['description'])?"Missing description":"")?></td>
					</tr>


					<tr>
						<td align="right">Paste the entire code here:</td>
						<td width="10"></td>
						<td><textarea class="t" name="code" style="width:250px;height:150px;" cols="10" rows="1"><?php echo @$_SESSION['code']?></textarea></td>
						<td class="error"><?php echo(sizeof($_POST) > 0 && empty($_SESSION['code'])?"Missing code":"")?></td>
					</tr>
					<tr>
						<td></td>
						<td width="10"></td>
						<td><input type="submit" value="Submit"/></td>

					</tr>
				</table>
			</form>
			<?php 
			}
			?>
			</div>



		</div>
	</div><!-- middle -->
	<div class="right">
	</div>
</div><!-- main -->
</body>
</html>