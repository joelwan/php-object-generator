<?php
session_start();
include "configuration.php";
include "objects/class.database.php";
include "objects/class.plugin.php";

$plugin = new plugin();
$list = $plugin->GetList(array(array("active","<>",0)));
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
	<div class="middle" style="background:url(plugins_splash.jpg) top 300px no-repeat;height:600px;margin-left:20px;">
		<div style="background:url(plugins_splash.jpg) no-repeat;width:300px;height:90px;float:left;margin-left:120px;"></div>
		<div style="width:90%;height:400px;margin-top:95px;">
			<?php
				foreach ($list as $aPlugin){

			?>
			<div style="float:left;width:100%;height:80px;font-weight:normal;background:#F7F7F7;padding:5px 0 0 5px;">
				<span style="font-size:14px;font-weight:bold;"><?php echo $aPlugin->name?></span> (<?php echo $aPlugin->php?>,<?php echo $aPlugin->mysql?>)<br />
				by <?php echo $aPlugin->author?><br /><br  />

				<?php echo $aPlugin->description?><br />
			</div>
			<div style="float:left;text-align:right;width:100%;margin:0;padding:0;height:20px;margin-bottom:20px;"><a href="./download-plugin.php?id=<?php echo $aPlugin->pluginId?>">Download</a></div>
			<?php
				}
			?>
		</div>
	</div><!-- middle -->
	<div class="right">
	</div>
</div><!-- main -->
</body>
</html>
<?php
	unset($_SESSION);
?>