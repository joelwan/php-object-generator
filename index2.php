<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/misc.php";
include "./include/configuration.php";
if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
{
	include "./services/nusoap.php";
}

if (IsPostback())
{
	$_GET = null;
	$objectName = GetVariable('object');
	$attributeList=Array();
	$typeList=Array();
	$classList=Array();
	$z=0;
	for ($i=1; $i<100; $i++)
	{
		if (GetVariable(('fieldattribute_'.$i)) != null)
		{
			$attributeList[] = GetVariable(('fieldattribute_'.$i));
			$z++;
		}
		if (GetVariable(('type_'.$i)) != null && $z==$i)
		{
			if (GetVariable(('type_'.$i)) != "OTHER"  && GetVariable(('ttype_'.$i)) == null)
			{
				$typeList[] = GetVariable(('type_'.$i));
			}
			else
			{
				$typeList[] = GetVariable(('ttype_'.$i));
			}
		}
		else
		{
			//attribute may have been removed. proceed to next row
			$z++;
		}

		if (GetVariable(('type_'.$i)) == "BELONGSTO" || GetVariable(('type_'.$i)) == "HASMANY"){
			$classList[] = GetVariable(('tclass_'.$i));
		}
		else{
			$classList[] = '';
		}
	}

	$_SESSION['objectName'] = $objectName;
	$_SESSION['language'] = $language = GetVariable('language');
	$_SESSION['wrapper'] = $wrapper = GetVariable('wrapper');
	$_SESSION['pdoDriver'] = $pdoDriver = GetVariable('pdoDriver');


if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
{
		$client = new soapclient($GLOBALS['configuration']['soap'], true);
		$params = array(
			    'objectName' 	=> $objectName,
			    'attributeList' => $attributeList,
			    'typeList'      => $typeList,
			    'language'      => $language,
			    'wrapper'       => $wrapper,
			    'pdoDriver'     => $pdoDriver
			);

		$object = base64_decode($client->call('GenerateObject', $params));
		//echo $client->debug_str;
		$_SESSION['objectString'] = $object;
		$_SESSION['attributeList'] = serialize($attributeList);
		$_SESSION['typeList'] = serialize($typeList);
		$_SESSION['classList'] = serialize($classList);
}
else if ($GLOBALS['configuration']['soapEngine'] == "phpsoap")
{
	$client = new SoapClient('services/pog.wsdl', array('cache_wsdl' => 0));
	try
	{
		$object = base64_decode($client->GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver, $classList));
		$_SESSION['objectString'] = $object;
		$_SESSION['attributeList'] = serialize($attributeList);
		$_SESSION['typeList'] = serialize($typeList);
		$_SESSION['classList'] = serialize($classList);
	}
	catch (SoapFault $e)
	{
		echo "Error: {$e->faultstring}";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Php Object Generator (<?php echo $GLOBALS['configuration']['versionNumber']?><?php echo $GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
		<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
		<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
		<link rel="shortcut icon" href="favicon.ico" >
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
		<script type="text/javascript">
			_uacct = "UA-72762-1";
			urchinTracker();
		</script>
	</head>
	<body>
		<div class="main">
			<div class="left2">
				<img src="./images/aboutphpobjectgenerator.jpg" alt="About Php Object Generator"/>
				<br />PHP Object Generator, (POG) is an open source <h1>PHP code generator</h1>&nbsp;which automatically generates clean &amp; tested Object Oriented code for your PHP4/PHP5 application. Over the years, we realized that a large portion of a PHP programmer's time is wasted on repetitive coding of the Database Access Layer of an application simply because different applications require different objects.
				<br />
				<br />By generating PHP objects with integrated CRUD methods, POG gives you a head start in any project. The time you save can be spent on more interesting areas of your project.<br /><a href="http://www.phpobjectgenerator.com/php_code_generator/introduction_php_code_generator.php" title="extended introduction to pog">Read more &#8250;&#8250;</a>
				<br />
				<br /><img src="./images/keyfeaturesphpobjectgenerator.jpg" alt="Key Features of  Php Object Generator"/>
				<br />Generates clean &amp; tested code
				<br />Generates CRUD methods
				<br />Generates setup file
				<br />Generates parent-child relations
				<br />Generates Setup file
				<br />Compatible with PHP4 &amp; PHP5
				<br />Compatible with PDO
				<br />Automatic data encoding
				<br />Free Developer SOAP API
				<br />Free for personal use
				<br />Free for commercial use
				<br />Open Source
				<br />
				<br />
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
				</form>
				<br />
				<br /><img src="./images/wantmorepog.jpg" alt="Want more Php Object Generator?"/>
				<br /><a href="http://www.phpobjectgenerator.com/plog" title="php object generator weblog">The POG Weblog</a> and <a href="http://www.phpobjectgenerator.com/plog/rss/" title="POG RSS feed">RSS feed</a>.
				<br /><a href="http://groups.google.com/group/Php-Object-Generator" title="Php object generator google group">The POG Google group</a>
				<br /><a href="http://www.phpobjectgenerator.com/plog/tutorials" title="php object generator tutorials and documentation">The POG Tutorials</a>
				<br /><a href="http://plugins.phpobjectgenerator.com" title="POG Plugins">POG Plugins</a>
			</div>
			<!-- left -->
			<div class="middle">
				<div class="header2"></div>
				<!-- header -->
				<form method="post" action="index3.php">
					<div class="result">
						<input type="image" src="./images/download.jpg"/>
					</div>
					<!-- result -->
					<div class="greybox2">
						<textarea cols="200" rows="30"><?php echo $object;?></textarea>
					</div>
					<!-- greybox -->
					<div class="generate2"></div>
					<!-- generate -->
					<div class="restart">
						<a href="./index.php"><img src="./images/back1.gif" border="0"/></a><br />
						<a href="./restart.php"><img src="./images/back2.gif" border="0"/></a>
					</div>
					<!-- restart -->
				</form>
			</div><!-- middle -->
			<div class="right2">
				<script type="text/javascript"><!--
					google_ad_client = "pub-7832108692498114";
					google_alternate_color = "FFFFFF";
					google_ad_width = 160;
					google_ad_height = 600;
					google_ad_format = "160x600_as";
					google_ad_type = "text";
					google_ad_channel = "9663063625";
					google_color_border = "FFFFFF";
					google_color_bg = "FFFFFF";
					google_color_link = "716500";
					google_color_url = "B8B8B8";
					google_color_text = "CCC078";
				//--></script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</div>
		</div><!-- main -->
	</body>
</html>
<?php
	$_POST = null;
}
else
{
	header("Location:/");
}
?>
