<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
* LIVE SITE
*/
include "./include/class.misc.php";
include "./include/configuration.php";
$misc = new Misc(array());
session_cache_limiter('nocache');
$cache_limiter = session_cache_limiter();
session_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Expires: 0');

if ($misc->GetVariable('objectName')!= null)
{
	$objectName = $misc->GetVariable('objectName');
}
if ($misc->GetVariable('attributeList') != null)
{
	if (isset($_GET['attributeList']))
		$attributeList = stripcslashes(urldecode($_GET['attributeList']));
	else
		@$attributeList=unserialize($_SESSION['attributeList']);
}
if ($misc->GetVariable('typeList') != null)
{
	if (isset($_GET['typeList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$typeList = stripcslashes(urldecode($_GET['typeList']));
		}
		else
		{
			$typeList = urldecode($_GET['typeList']);
		}
		$typeList = trim($typeList);
		for($i=0; $i<sizeof($typeList); $i++)
		{
			$typeList[$i] = stripcslashes($typeList[$i]);
		}
	}
	else
	{
		@$typeList = unserialize($_SESSION['typeList']);
		if (count($typeList) == 0)
		{
			$typeList = null;
		}
	}
}
if ($misc->GetVariable('classList') != null)
{
	if (isset($_GET['classList']))
		$classList = stripcslashes(urldecode($_GET['classList']));
	else
		@$classList=unserialize($_SESSION['classList']);
}

$pdoDriver = ($misc->GetVariable('pdoDriver')!=null?$misc->GetVariable('pdoDriver'):'mysql');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" >
<title>Php Object Generator (v<?php echo $GLOBALS['configuration']['versionNumber']?><?php echo $GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
<meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="ICBM" content="53.5411, -113.4914">
<meta name="DC.title" content="PHP Object Generator (POG)">
<script src="jquery.js" type="text/javascript"></script>
<script src="pog1.js" type="text/javascript"></script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-72762-1";
urchinTracker();
</script>
</head>
<body id="frame">
<div class="main">
	<div class="left">
		<img src="./images/aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/><br/>PHP Object Generator, (POG) is an open source <h1>PHP code generator</h1>&nbsp;which automatically generates clean &amp; tested Object Oriented code for your PHP4/PHP5 application. Over the years, we realized that a large portion of a PHP programmer's time is wasted on repetitive coding of the Database Access Layer of an application simply because different applications require different objects.
		<br /><br/>By generating PHP objects with integrated CRUD methods, POG gives you a head start in any project. The time you save can be spent on more interesting areas of your project.<br/><a href="http://www.phpobjectgenerator.com/php_code_generator/introduction_php_code_generator.php" title="extended introduction to pog">Read more &#8250;&#8250;</a>
		<br /><br/><img src="./images/keyfeaturesphpobjectgenerator.jpg" alt="Key Features of  Php Object Generator"/>
		<br />Fast and scalable
		<br />Generates clean+tested code
		<br />Generates CRUD methods
		<br />Generates setup file
		<br />Generates parent-child relations
		<br />Generates sibling relations
		<br />Extensible via plugins
		<br />Compatible with PHP4 &amp; PHP5
		<br />Compatible with PDO
		<br />Automatic data encoding
		<br />Free Developer SOAP API
		<br />Free for personal use
		<br />Free for commercial use
		<br />Open Source
		<br /><br/>
		<form class="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="pogguys@phpobjectgenerator.com">
		<input type="hidden" name="item_name" value="PHP Object Generator Donation">
		<input type="hidden" name="page_style" value="PayPal">
		<input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="return" value="http://www.phpobjectgenerator.com">
		<input type="hidden" name="cancel_return" value="http://www.phpobjectgenerator.com">
		<input type="hidden" name="cn" value="Thanks for making a donation!">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="tax" value="0">
		<input type="hidden" name="bn" value="PP-DonationsBF">
		<input type="image" src="http://www.phpobjectgenerator.com/images/php_code_generator_donate.gif" border="0" name="submit" alt="Thank you!">
		</form><br/><br/><img src="./images/wantmorepog.jpg" alt="Want more Php Object Generator?"/>
		<br/><a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">The POG Weblog</a> and <a href="http://www.phpobjectgenerator.com/plog/rss/" title="POG RSS feed">RSS feed</a>
		<br/><a href="http://groups.google.com/group/Php-Object-Generator" title="Php object generator google group">The POG Google group</a>
		<br/><a href="http://www.phpobjectgenerator.com/plog/tutorials" title="php object generator tutorials and code samples">The POG tutorials/code samples</a>
		<br/><a href="http://plugins.phpobjectgenerator.com" title="POG Plugins">POG Plugins</a>
		<br/><a href="https://github.com/joelwan/php-object-generator" title="php object generator on github">The POG source code</a>
		<?php include("include/inc.tla.php"); ?>
		</div><!-- left -->

	<div class="middle">
		<div class="header">
		</div><!-- header -->
		<form method="post" action="index2.php">
		<div class="customize">
			<select class="s" name="language" id="FirstField" onchange="CascadePhpVersion()">
				<option value="php5" <?php echo($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5"?"selected":"")?>>PHP 5</option>
				<option value="php5.1" <?php echo($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5.1"?"selected":"")?>>PHP 5.1+</option>
				<option value="php4" <?php echo($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php4"?"selected":"")?>>PHP 4</option>
			</select>
			<br/><br/>
			<select class="s" name="wrapper" id="wrapper" onchange="IsPDO()">
				<option value="POG"  <?php echo ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="POG"?"selected":"")?>>POG</option>
				<?php
				if (($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"))
				{
				?>
					<option value="PDO" <?php echo ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"selected":"")?>>PDO</option>
				<?php
				}
				?>
			</select>
			<select class="s" name="pdoDriver" id="PDOdriver" style="display:<?php echo ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"inline":"none")?>">
				<option value="mysql" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="mysql"?"selected":"")?>>MYSQL</option>
				<!--<option value="oci" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="oci"?"selected":"")?>>OCI</option>-->
				<!--<option value="dblib" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="dblib"?"selected":"")?>>DBLIB</option>-->
				<!--untested pdo drivers have been commented out. uncomment once they are tested-->
				<!--<option value="firebird" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="firebird"?"selected":"")?>>FIREBIRD</option>
				<option value="odbc" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="odbc"?"selected":"")?>>ODBC</option>
				<option value="pgsql" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="pgsql"?"selected":"")?>>PGSQL</option>
				<option value="sqlite" <?php echo ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="sqlite"?"selected":"")?>>SQLITE</option>-->
			</select>

			<a id="disappear" style="display:<?php echo ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"none":"inline")?>" href="http://www.phpobjectgenerator.com/php_code_generator/php_code_generator_wrapper.php" target="_blank"><img src="./images/whatsthis.jpg" border="0" alt="what's this?"/></a>
		</div><!-- customize -->
		<div class="objectname">
			<input type="text" id="objName" name="object" class="i" value="<?php echo(isset($objectName)?$objectName:'')?>"/>
		</div><!-- objectname -->
		<div class="greybox">
			<span class="line" id="line_1"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/>
				<input  type="text" id="fieldattribute_1" name="fieldattribute_1" class="i f" value="<?=(isset($attributeList)&&isset($attributeList[0])?$attributeList[0]:'')?>" ></input>  &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/>
                <select class="s typ" style="display:<?=(!isset($typeList[0])||$misc->TypeIsKnown($typeList[0]) ?"inline":"none")?>" name="type_1" id="type_1">
                	<?
                		$dataTypeIndex = 0;
						eval("include \"include/datatype.mysql.inc.php\";");
					?>
                </select>
              	<input style="display:<?=(!isset($typeList[0])||$misc->TypeIsKnown($typeList[0])?"none":"inline")?>" type="text" name="ttype_1" class="i" id="ttype_1" value="<?=(isset($typeList)&&isset($typeList[0])&&!$misc->TypeIsKnown($typeList[0])?$typeList[0]:'')?>"></input>
              	&nbsp;&nbsp;
              	<img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:<?=(isset($typeList[0])&&$typeList[0] == "BELONGSTO" || isset($typeList[0])&&$typeList[0] == "HASMANY"?"inline":"none")?>"/ >
              	<input style="display:<?=(isset($typeList[0])&&$typeList[0] == "BELONGSTO" || isset($typeList[0])&&$typeList[0] == "HASMANY"?"inline":"none")?>" type="text" name="tclass_1" class="i" id="tclass_1" value="<?=(isset($classList)&&isset($classList[0])?$classList[0]:'')?>"></input>
            </span><br/><br/>
			<span class="line" id="line_2"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/>
				<input type="text" id="fieldattribute_2" name="fieldattribute_2" class="i f" value="<?=(isset($attributeList)&&isset($attributeList[1])?$attributeList[1]:'')?>" ></input> &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/>
				<select class="s typ" style="display:<?=(!isset($typeList[1])||$misc->TypeIsKnown($typeList[1]) ?"inline":"none")?>" name="type_2" id="type_2">
              		<?
                		$dataTypeIndex = 1;
						eval("include \"include/datatype.mysql.inc.php\";");
					?>
                </select>
                <input style="display:<?=(!isset($typeList[1])||$misc->TypeIsKnown($typeList[1]) ?"none":"inline")?>" type="text" name="ttype_2" class="i" id="ttype_2" value="<?=(isset($typeList)&&isset($typeList[1])&&!$misc->TypeIsKnown($typeList[1])?$typeList[1]:'')?>"></input>
                &nbsp;&nbsp;
                <img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:<?=(isset($typeList[1])&&$typeList[1] == "BELONGSTO" || isset($typeList[1])&&$typeList[1] == "HASMANY"?"inline":"none")?>"/>
                <input style="display:<?=(isset($typeList[1])&&$typeList[1] == "BELONGSTO" || isset($typeList[0])&&$typeList[1] == "HASMANY"?"inline":"none")?>" type="text" name="tclass_2" class="i" id="tclass_2" value="<?=(isset($classList)&&isset($classList[1])?$classList[1]:'')?>"></input>
        	</span><br/><br/>
			<span class="line" id="line_3"><img src="./images/object2.jpg" width="33" height="29" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute" width="56" height="18"/>
				<input type="text" id="fieldattribute_3" name="fieldattribute_3" class="i f" value="<?=(isset($attributeList)&&isset($attributeList[2])?$attributeList[2]:'')?>" ></input> &nbsp;&nbsp;<img src="./images/type.jpg" width="36" height="18" alt="object attribute"/>
				<select class="s typ" style="display:<?=(!isset($typeList[2])||$misc->TypeIsKnown($typeList[2]) ?"inline":"none")?>" name="type_3" id="type_3">
	                	<?
	                		$dataTypeIndex = 2;
							eval("include \"include/datatype.mysql.inc.php\";");
						?>
				</select>
                <input style="display:<?=(!isset($typeList[2])||$misc->TypeIsKnown($typeList[2]) ?"none":"inline")?>" type="text" name="ttype_3" class="i" id="ttype_3" value="<?=(isset($typeList)&&isset($typeList[2])&&!$misc->TypeIsKnown($typeList[2])?$typeList[2]:'')?>"></input>
                &nbsp;&nbsp;
                <img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:<?=(isset($typeList[2])&&$typeList[2] == "BELONGSTO" || isset($typeList[2])&&$typeList[2] == "HASMANY"?"inline":"none")?>"/>
                <input style="display:<?=(isset($typeList[2])&&$typeList[2] == "BELONGSTO" || isset($typeList[0])&&$typeList[2] == "HASMANY"?"inline":"none")?>" type="text" name="tclass_3" class="i" id="tclass_3" value="<?=(isset($classList)&&isset($classList[2])?$classList[2]:'')?>"></input>
            </span><br/>
		<?php
		if (isset($attributeList))
		{
			$max = count($attributeList);
			for ($j=4; $j<= $max; $j++)
			{
				echo '<div style="display:block" id="attribute_'.$j.'">
					<br/><span class="line" id="line_'.$j.'"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>
					<input type="text" name="fieldattribute_'.$j.'" class="i f" id="fieldattribute_'.$j.'" value="'.(isset($attributeList)&&isset($attributeList[$j-1])?$attributeList[$j-1]:'').'" /> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/>
					<select class="s typ" style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1])?"inline":"none").'" name="type_'.$j.'" id="type_'.$j.'">';

				$dataTypeIndex = $j-1;
				eval("include \"./include/datatype.mysql.inc.php\";");

				echo '</select>
                <input style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1]) ?"none":"inline").'" type="text" id="ttype_'.$j.'"  name="ttype_'.$j.'" class="i" value="'.(isset($typeList)&&isset($typeList[$j-1])&&!$misc->TypeIsKnown($typeList[$j-1])?$typeList[$j-1]:'').'"></input>
                &nbsp;&nbsp;
                <img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:'.(isset($typeList[$j-1])&&$typeList[$j-1] == "BELONGSTO" || isset($typeList[$j-1])&&$typeList[$j-1] == "HASMANY"?"inline":"none").'"/>
                <input style="display:'.(isset($typeList[$j-1])&&$typeList[$j-1] == "BELONGSTO" || isset($typeList[$j-1])&&$typeList[$j-1] == "HASMANY"?"inline":"none").'" type="text" name="tclass_'.$j.'" class="i" id="tclass_'.$j.'" value="'.(isset($classList)&&isset($classList[$j-1])?$classList[$j-1]:'').'"></input>
                </span><br/>
				</div>';
			}

			if ($max < 3)
			{
				$max = 3;
			}
			for ($j=$max+1; $j<100; $j++)
			{
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line" id="line_'.$j.'"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>
					<input type="text" name="fieldattribute_'.$j.'" class="i f" id="fieldattribute_'.$j.'" value="" /> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/>
				<select class="s typ" style="display:inline" name="type_'.$j.'" id="type_'.$j.'">';

				$dataTypeIndex = $j;
				eval("include \"./include/datatype.mysql.inc.php\";");

				echo '</select>
				<input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input>
				&nbsp;&nbsp;
				<img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:none"/>
                <input style="display:none" type="text" name="tclass_'.$j.'" class="i" id="tclass_'.$j.'"></input>
                </span>
				<br/>
				</div>';

			}
		}
		else
		{
			for ($j=4; $j<100; $j++)
			{

				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line" id="line_'.$j.'"><img src="./images/object2.jpg" alt="object attribute"/><img src="./images/attribute.jpg" alt="object attribute"/>
					<input type="text" name="fieldattribute_'.$j.'" class="i f" id="fieldattribute_'.$j.'" /> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute"/>
				<select class="s typ" style="display:inline" name="type_'.$j.'" id="type_'.$j.'">';

                $dataTypeIndex = $j;
				eval("include \"./include/datatype.mysql.inc.php\";");


                echo '</select>
				<input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input>
				&nbsp;&nbsp;
				<img src="./images/class.jpg" height="18" alt="object attribute" style="margin-left:5px; display:none"/>
                <input style="display:none" type="text" name="tclass_'.$j.'" class="i" id="tclass_'.$j.'"></input>
				</span><br/>
				</div>';
			}
		}
		?>
		</div><!-- greybox -->
		<div class="generate">
			<a href="#" onclick="AddField();return false;"><img src="./images/addattribute.jpg" border="0" alt="add attribute"/></a> <a href="#" onclick="ResetFields();return false"><img src="./images/resetfields.jpg" border="0" alt="reset fields"/></a>
		</div><!-- generate -->

		<div class="submit">
			<input type="image"  src="./images/generate.jpg" alt="Generate!"/>
		</div><!-- submit -->
		</form>
	</div><!-- middle -->
	<div class="right">
	<img src="./images/php_code_generator_counter.gif" alt="PHP Object Generator counter"/>
<script type="text/javascript"><!--
google_ad_client = "pub-7832108692498114";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text";
google_ad_channel = "8639589622";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "716500";
google_color_url = "B8B8B8";
google_color_text = "CCC078";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div>
</div><!-- main -->
</body>
</html>
<?php
	unset($_SESSION);
?>