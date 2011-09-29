<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
<link rel="stylesheet" href="./code_generator_primer.css" type="text/css" />
<title>PHP Code Generator</title>
<meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
<meta name="keywords" content="php code generator, classes, object-oriented, CRUD" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="./pog.js" type="text/javascript">
</script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-72762-1";
urchinTracker();
</script>
</head>
<body class="primer">
<div class="main">
	<div class="left">

	</div><!-- left -->

	<div class="middle">
<h1>Database Wrappers</h1>
<p>One of our obsessions is to generate the cleanest objects possible so that they're easy to understand and use. To achieve this, we need to make sure the same code is not unnecessarily repeated across objects. Therefore, <i><a href="http://www.phpobjectgenerator.com" title="PHP Object generator">PHP Object generator</a></i> abstracts common database-related code into a separate database class (class.database.php) which is also provided to you upon code generation. We call this database class the <b><i>POG database wrapper</i></b>.</p>

<p>There is another wrapper that you can choose if you're generating code for PHP 5.1 & above: <i><a href="http://www.php.net/pdo" title="PHP PDO">PDO</i></a>. PDO is the new lightweight and consistent database interface for PHP and is one of the new features included in the PHP distribution as of version 5.1. Unlike the POG wrapper or any other external abstraction layer such as PEAR DB, PDO is part of the PHP core, which means that PDO functions can be called just like any other PHP functions without the need to include any external files. <span class="grey">PHP Object Generator can generate objects compatible with PDO and in fact, PDO is the default wrapper selected by POG whenever a user chooses to generate code for PHP 5.1 & above</span>.</p>

<h3>Which wrapper should you choose?</h3>

<table border="1" align="center">
<tr><th>PHP Version</th><th>We recommend choosing:</th></tr>
<tr><td align="center">5.1 & above</td><td align="center">PDO</td>
<tr><td align="center">5.0 & below</td><td align="center">POG <i>(no other choice)</i></td>
</tr>
</table>

<br/><br/>
<hr/>

<h2>Additional Information</h2>

<h3>What is a database wrapper?</h3>

<p>In programming, a wrapper is a component which changes the interface of an existing component, in order to increase its compatibility with various other systems.</p>

<p>Because there are many types of databases, each with their own interface, a popular technique used in database programming is to create a wrapper which offers a common interface for database interaction. As an analogy, you can think of a database wrapper as an "interpreter" who knows all the different languages spoken by the different databases. So you can communicate to the "interpreter" in your own language, and it will take care of translating your request, obtain your result and translate it back in a language that you understand. </p>

<h3>Why do generated objects need a database wrapper?</h3>

<p>As described in the <i><a href="http://www.phpobjectgenerator.com/php_code_generator/introduction_php_code_generator.php" title="Introduction to POG">Introduction to POG</a></i>, POG generates objects which use the Object Relational Mapping, or ORM, programming pattern where "objects" are used to represent rows in a database table. Each object also comes with 5 integrated CRUD methods which allow you to perform atomic database operations. </p>

<p>Instead of repeating the code that performs those database operations across objects, we've abstracted, or grouped them, in a separate database class. This Database class (class.database.php) is required by your object so that it can perform database operations such as "Save", "Delete", "Get" etc. It's also important to note that currently, this database wrapper is specific to MySQL only and won't work if you're planning to use other databases, such as Oracle.


<h2>More about PDO</h2>
<p><b>From the PHP website</b><br/>
The PHP Data Objects (PDO) extension defines a lightweight, consistent interface for accessing databases in PHP. Each database driver that implements the PDO interface can expose database-specific features as regular extension functions. Note that you cannot perform any database functions using the PDO extension by itself; you must use a database-specific PDO driver to access a database server.</p>

<p>PDO provides a data-access abstraction layer, which means that, regardless of which database you're using, you use the same functions to issue queries and fetch data. PDO does not provide a database abstraction; it doesn't rewrite SQL or emulate missing features. You should use a full-blown abstraction layer if you need that facility.

<p>PDO ships with PHP 5.1, and is available as a PECL extension for PHP 5.0; PDO requires the new OO features in the core of PHP 5, and so will not run with earlier versions of PHP.</p>
	</div><!-- middle -->
	<div class="right" style="padding-left:100px;">
<script type="text/javascript"><!--
google_ad_client = "pub-7832108692498114";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_cpa_choice = "CAEaCD6CK5N5V0NTUAVQA1AIUEM";
google_ad_channel = "6934687074";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_text = "333333";
google_color_url = "0000FF";
//-->
</script>
<script type="text/javascript"><!--
google_ad_client = "pub-7832108692498114";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_cpa_choice = "CAEaCDFo_JLOUFdYUC9QNFAS";
google_ad_channel = "7811436112";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_text = "333333";
google_color_url = "0000FF";
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div>
</div><!-- main -->
</body>
</html>