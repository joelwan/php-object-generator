<?php
if (!defined("PHPUnit_MAIN_METHOD"))
{
	define("PHPUnit_MAIN_METHOD", "EssentialsTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'app/configuration.php';
require_once 'app/objects/class.database.php';
require_once 'app/objects/class.object.php';
require_once 'app/objects/class.sibling.php';
require_once 'app/objects/class.parent_.php';
require_once 'app/objects/class.child.php';

/**
 * Test class for Relations.
 */
class EssentialsTest extends PHPUnit_Framework_TestCase
{
	public $object;
	public $sibling;
	public $sibling2;
	public $child;
	public $child2;
	public $parent_;

	/**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
	public static function main()
	{
		require_once "PHPUnit/TextUI/TestRunner.php";

		$suite  = new PHPUnit_Framework_TestSuite("EssentialsTest");
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
		$this->object = new object('obj att', 'obj att2');
	}

	/**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
	protected function tearDown()
	{
		$this->object->DeleteList(array(array("objectId", ">", 0)));
		unset($this->object);
	}


	///////////////////////
	/// ESSENTIAL TESTS ///
	///////////////////////

	/**
	 * Insert
	 *
	 */
	public function testSave_Insert()
	{
		$this->object->Save();

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(1, sizeof($objectList));
	}

	/**
	 * Update
	 *
	 */
	public function testSave_Update()
	{
		$this->object->Save();

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(1, sizeof($objectList));

		$object = $objectList[0];
		$object->attribute = "changed att";
		$object->Save();

		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(1, sizeof($objectList));
		$this->assertEquals("changed att", $objectList[0]->attribute);
	}

	/**
	 * Simple delete
	 *
	 */
	public function testDelete_Simple()
	{
		$this->object->Save();

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(1, sizeof($objectList));

		$this->object->Delete();
		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(0, sizeof($objectList));
	}

	/**
	 * Simple GetList
	 *
	 */
	public function testGetList_Simple()
	{
		$this->object->Save();
		$newObject1 = new object("newobject1 att");
		$newObject2 = new object("newobject2 att");
		$newObject3 = new object("newobject3 att");

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $this->object->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(4, sizeof($objectList));
	}

	/**
	 * Sorting
	 *
	 */
	public function testGetList_Sorting()
	{
		$this->object->Save();
		$newObject1 = new object("efg");
		$newObject2 = new object("abc");
		$newObject3 = new object("d");

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $newObject1->GetList(array(array("objectId", ">", 0)), "attribute", true);

		$this->assertEquals("abc", $objectList[0]->attribute);
		$this->assertEquals("d", $objectList[1]->attribute);
		$this->assertEquals("efg", $objectList[2]->attribute);
		$this->assertEquals("obj att", $objectList[3]->attribute);

		$objectList = $newObject1->GetList(array(array("objectId", ">", 0)), "attribute", false);

		$this->assertEquals("obj att", $objectList[0]->attribute);
		$this->assertEquals("efg", $objectList[1]->attribute);
		$this->assertEquals("d", $objectList[2]->attribute);
		$this->assertEquals("abc", $objectList[3]->attribute);

	}

	/**
	 * Limit
	 *
	 */
	public function testGetList_Limit()
	{
		$this->object->Save();
		$newObject1 = new object("efg");
		$newObject2 = new object("abc");
		$newObject3 = new object("d");

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $newObject1->GetList(array(array("objectId", ">", 0)), "", "", 2);

		$this->assertEquals(2, sizeof($objectList));
	}

	/**
	 * Sorting plus Limit. This is of particular interest
	 * when data is encoded in base64
	 *
	 */
	public function testGetList_SortingLimit()
	{
		$this->object->Save();
		$newObject1 = new object("efg");
		$newObject2 = new object("abc");
		$newObject3 = new object("d");

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $newObject1->GetList(array(array("objectId", ">", 0)), "attribute", true, 2);

		$this->assertEquals(2, sizeof($objectList));
		$this->assertEquals("abc", $objectList[0]->attribute);

		$objectList = $newObject1->GetList(array(array("objectId", ">", 0)), "attribute", false, 2);

		$this->assertEquals(2, sizeof($objectList));
		$this->assertEquals("obj att", $objectList[0]->attribute);
		$this->assertEquals("efg", $objectList[1]->attribute);
	}

	/**
	 * Tests column comparison
	 *
	 */
	public function testGetListColumn()
	{
		$this->object->Save();
		$newObject1 = new object("obj att", 'obj att2');
		$newObject2 = new object("obj att_diff", 'obj att_diff');
		$newObject3 = new object("obj att", 'obj att');

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $newObject1->GetList(array(array("attribute", "<>", "`attribute2`")));

		$this->assertEquals(2, sizeof($objectList));

		$objectList = $newObject1->GetList(array(array("attribute", "=", "`attribute2`")));

		$this->assertEquals(2, sizeof($objectList));
	}

	/**
	 * DNF
	 *
	 */
	public function testGetList_Or()
	{
		$this->object->Save();
		$newObject1 = new object("newobject1 att");
		$newObject2 = new object("newobject2 att");
		$newObject3 = new object("newobject3 att");

		$newObject1->Save();
		$newObject2->Save();
		$newObject3->Save();

		$objectList = $this->object->GetList(array(array("attribute", "=", "obj att")));
		$this->assertEquals(1, sizeof($objectList));

		$objectList = $this->object->GetList(array(array("attribute", "=", "obj att"),
													array("or"),
													array("attribute", "=", "newobject1 att")));
		$this->assertEquals(2, sizeof($objectList));

		$objectList = $this->object->GetList(array(array("attribute", "=", "obj att"),
													array("or"),
													array("attribute", "=", "newobject1 att"),
													array("or"),
													array("attribute", "=", "newobject2 att")));
		$this->assertEquals(3, sizeof($objectList));
	}

	/**
	 * Save new
	 *
	 */
	public function testSaveNew()
	{
		$this->object->Save();
		$this->object->SaveNew();

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(2, sizeof($objectList));
	}

	/**
	 * Delete list
	 *
	 */
	public function testDeleteList_Simple()
	{
		$this->object->Save();
		$this->object->SaveNew();
		$this->object->DeleteList(array(array("objectId", ">", 0)));

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(0, sizeof($objectList));
	}
}

// Call EssentialsTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "EssentialsTest::main")
{
	EssentialsTest::main();
}
?>
