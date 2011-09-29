<?php
if (!defined("PHPUnit_MAIN_METHOD"))
{
	define("PHPUnit_MAIN_METHOD", "SoapTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'app/configuration.php';

/**
 * Test class for Relations.
 */
class SoapTest extends PHPUnit_Framework_TestCase
{
	public $soap;

	/**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
	public static function main()
	{
		require_once "PHPUnit/TextUI/TestRunner.php";

		$suite  = new PHPUnit_Framework_TestSuite("SoapTest");
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
	protected function setUp()
	{
	}

	/**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
	protected function tearDown()
	{

	}


	///////////////////////
	/// SOAP TESTS 		///
	///////////////////////

	function testGetGeneratorVersion()
	{

	}

	function testGenerateObject()
	{

	}

	function testGenerateMapping()
	{

	}

	function testGenerateObjectFromLink()
	{
	}

	function testGeneratePackageFromLink()
	{
	}

	function testGenerateConfiguration()
	{

	}

	function GeneratePackage()
	{

	}
}

// Call SoapTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "SoapTest::main")
{
	SoapTest::main();
}
?>
