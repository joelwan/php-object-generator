<?php
if (!defined("PHPUnit_MAIN_METHOD"))
{
	define("PHPUnit_MAIN_METHOD", "RelationsTest::main");
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
class RelationsTest extends PHPUnit_Framework_TestCase
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

		$suite  = new PHPUnit_Framework_TestSuite("RelationsTest");
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
		$this->object = new object('obj att');

		$this->sibling = new sibling('sibling att');
		$this->sibling2 = new sibling('sibling att2');

		$this->child = new child('child att');
		$this->child2 = new child('child att2');

		$this->parent_ = new parent_('parent_ att');
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
		$this->child->DeleteList(array(array("childId", ">", 0)));
		$this->sibling->DeleteList(array(array("siblingId", ">", 0)));
		$this->parent_->DeleteList(array(array("parent_Id", ">", 0)));
		unset($this->object);
		unset($this->sibling);
		unset($this->sibling2);
		unset($this->child);
		unset($this->child2);
		unset($this->parent_);
	}


	///////////////////////
	/// RELATIONS TESTS ///
	///////////////////////

	/**
	 * Simple child add
	 *
	 */
	public function testAddChild_Simple()
	{
		$this->object->AddChild($this->child);
		$this->assertType("child", $this->object->childList[0]);
		$this->assertEquals(1, sizeof($this->object->childList));
	}

	/**
	 * There should be no duplicates in childList
	 *
	 *
	 */
	public function testAddChild_Duplicate()
	{
		$this->child->Save();
		$this->object->AddChild($this->child);
		$this->object->AddChild($this->child);
		$this->assertEquals(1, sizeof($this->object->childList));
	}

	/**
	 * Modify child's attribute after it has been added
	 *
	 */
	public function testAddChild_Modification()
	{
		$this->object->AddChild($this->child);
		$this->child->attribute = "changed att";
		$this->assertEquals("changed att", $this->object->childList[0]->attribute);
	}

	/**
	 * Adding children through a loop
	 *
	 */
	public function testAddChild_Loop()
	{
		$someChild1 = new child('somechild1 att');
		$someChild2 = new child('somechild2 att');
		$someChild3 = new child('somechild3 att');

		$childList = array($someChild1, $someChild2, $someChild3);

		foreach ($childList as $child)
		{
			$this->object->AddChild($child);
		}

		$this->assertEquals('somechild1 att', $this->object->childList[0]->attribute);
		$this->assertEquals('somechild2 att', $this->object->childList[1]->attribute);
		$this->assertEquals('somechild3 att', $this->object->childList[2]->attribute);
	}

	/**
	 * Simple sibling add
	 *
	 */
	public function testAddSibling_Simple()
	{
		$this->object->AddSibling($this->sibling);
		$this->assertType("sibling", $this->object->siblingList[0]);
		$this->assertEquals(1, sizeof($this->object->siblingList));
	}

	/**
	 * There should be no duplicates in siblingList
	 *
	 */
	public function testAddSibling_Duplicate()
	{
		$this->sibling->Save();
		$this->object->AddSibling($this->sibling);
		$this->object->AddSibling($this->sibling);
		$this->assertEquals(1, sizeof($this->object->siblingList));
	}

	/**
	 * Modify sibling's attribute after it has been added
	 *
	 */
	public function testAddSibling_Modification()
	{
		$this->object->AddSibling($this->sibling);
		$this->sibling->attribute = "changed att";
		$this->assertEquals("changed att", $this->object->siblingList[0]->attribute);
	}

	/**
	 * Adding sibling through a loop
	 *
	 */
	public function testAddSibling_Loop()
	{
		$someSibling1 = new sibling('somesibling1 att');
		$someSibling2 = new sibling('somesibling2 att');
		$someSibling3 = new sibling('somesibling3 att');

		$siblingList = array($someSibling1, $someSibling2, $someSibling3);

		foreach ($siblingList as $sibling)
		{
			$this->object->AddSibling($sibling);
		}

		$this->assertEquals('somesibling1 att', $this->object->siblingList[0]->attribute);
		$this->assertEquals('somesibling2 att', $this->object->siblingList[1]->attribute);
		$this->assertEquals('somesibling3 att', $this->object->siblingList[2]->attribute);
	}

	/**
	 * There should be no circular dependencies created while adding siblings
	 *
	 */
	public function testAddSibling_CircularDependency()
	{
		$this->object->AddSibling($this->sibling);
		$this->sibling->AddObject($this->object);
		$this->assertEquals(0, sizeof($this->sibling->objectList));
	}

	/**
	 * Simple get children
	 *
	 */
	public function testGetChildren_Simple()
	{
		$this->object->AddChild($this->child);
		$this->object->AddChild($this->child2);
		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)));
		$this->assertEquals(0, sizeof($childrenList));
		$this->object->Save();
		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)));
		foreach ($childrenList as $innerChild)
		{
			$this->assertType("child", $innerChild);
		}
		$this->assertEquals(2, sizeof($childrenList));
	}

	/**
	 * Calling GetChildren multiple times within the same
	 * code block should produce the same results
	 *
	 */
	public function testGetChildren_Duplicate()
	{
		$this->object->AddChild($this->child);
		$this->object->AddChild($this->child2);
		$this->object->Save();
		$this->object->GetChildList(array(array("childId", ">", 0)));
		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)));
		foreach ($childrenList as $innerChild)
		{
			$this->assertType("child", $innerChild);
		}
		$this->assertEquals(2, sizeof($childrenList));
	}

	/**
	 * Should return children that are in the db and those that haven't yet
	 * been committed
	 *
	 */
	public function testGetChildren_CommittedNotCommitted()
	{
		$this->object->AddChild($this->child);
		$this->object->Save();
		$this->object->AddChild($this->child2);

		$childrenList = $this->object->GetChildList(array(array("attribute", "=", "child att")));
		$this->assertEquals(1, sizeof($childrenList));

		$childrenList = $this->object->GetChildList(array(array("attribute", "=", "child2 att")));
		$this->assertEquals(0, sizeof($childrenList));

		$childrenList = $this->object->GetChildList(array(array("attribute", "=", "child att"), array("or"), array("attribute", "=", "child2 att")));
		$this->assertEquals(1, sizeof($childrenList));
	}

	/**
	 * Calling GetChildren multiple times using different conditions
	 * within the same code block should produce the correct result
	 *
	 */
	public function testGetChildren_DifferentConditions()
	{
		$this->object->AddChild($this->child);
		$this->object->AddChild($this->child2);
		$this->object->Save();
		$childrenList = $this->object->GetChildList(array(array("attribute", "=", "child att")));
		$this->assertEquals(1, sizeof($childrenList));
		$childrenList = $this->object->GetChildList(array(array("attribute", "=", "child att2")));
		$this->assertEquals(1, sizeof($childrenList));
	}

	/**
	 * Tests DNF
	 *
	 */
	public function testGetChildren_Or()
	{
		$this->object->AddChild($this->child);
		$this->object->AddChild($this->child2);

		$this->object->Save();

		$this->object->childList = array();

		$childrenList = $this->object->GetChildList(array(array("childId", ">", "child att"),
														array("or"),
														array("attribute", "=", "child att2")));
		$this->assertEquals(2, sizeof($childrenList));
	}

	/**
	 * Tests Sorting
	 *
	 */
	public function testGetChildren_Sorting()
	{
		$this->object->AddChild($this->child);
		$newChild1 = new child("efg");
		$newChild2 = new child("abc");
		$newChild3 = new child("d");

		$this->object->AddChild($newChild1);
		$this->object->AddChild($newChild2);
		$this->object->AddChild($newChild3);

		$this->object->Save();

		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)), "attribute", true);

		$this->assertEquals("abc", $childrenList[0]->attribute);
		$this->assertEquals("child att", $childrenList[1]->attribute);
		$this->assertEquals("d", $childrenList[2]->attribute);
		$this->assertEquals("efg", $childrenList[3]->attribute);

		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)), "attribute", false);

		$this->assertEquals("efg", $childrenList[0]->attribute);
		$this->assertEquals("d", $childrenList[1]->attribute);
		$this->assertEquals("child att", $childrenList[2]->attribute);
		$this->assertEquals("abc", $childrenList[3]->attribute);
	}

	/**
	 * Limit
	 *
	 */
	public function testGetChildren_Limit()
	{
		$this->object->AddChild($this->child);
		$newChild1 = new child("efg");
		$newChild2 = new child("abc");
		$newChild3 = new child("d");

		$this->object->AddChild($newChild1);
		$this->object->AddChild($newChild2);
		$this->object->AddChild($newChild3);

		$this->object->Save();

		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)), "", "", 2);

		$this->assertEquals(2, sizeof($childrenList));
	}

	/**
	 * Sorting plus Limit. This is of particular interest when data is encoded.
	 *
	 */
	public function testGetChildren_SortingLimit()
	{
		$this->object->AddChild($this->child);
		$newChild1 = new child("efg");
		$newChild2 = new child("abc");
		$newChild3 = new child("d");

		$this->object->AddChild($newChild1);
		$this->object->AddChild($newChild2);
		$this->object->AddChild($newChild3);

		$this->object->Save();

		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)), "attribute", true, 2);

		$this->assertEquals("abc", $childrenList[0]->attribute);
		$this->assertEquals("child att", $childrenList[1]->attribute);

		$childrenList = $this->object->GetChildList(array(array("childId", ">", 0)), "attribute", false, 2);

		$this->assertEquals("efg", $childrenList[0]->attribute);
		$this->assertEquals("d", $childrenList[1]->attribute);
	}

	/**
	 * Simple get sibling
	 *
	 */
	public function testGetSibling_Simple()
	{
		$this->object->AddSibling($this->sibling);
		$this->object->AddSibling($this->sibling2);
		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)));
		$this->assertEquals(0, sizeof($siblingList));

		$this->object->Save();
		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)));
		foreach ($siblingList as $innerSibling)
		{
			$this->assertType("sibling", $innerSibling);
		}
		$this->assertEquals(2, sizeof($siblingList));
	}

	/**
	 * Calling GetSibling multiple times within the same
	 * code block should produce the same results
	 *
	 */
	public function testGetSibling_Duplicate()
	{
		$this->object->AddSibling($this->sibling);
		$this->object->AddSibling($this->sibling2);
		$this->object->Save();
		$this->object->GetSiblingList(array(array("siblingId", ">", 0)));
		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)));
		foreach ($siblingList as $innerSibling)
		{
			$this->assertType("sibling", $innerSibling);
		}
		$this->assertEquals(2, sizeof($siblingList));
	}

	/**
	 * Should return siblings that are in the db as well as those
	 * still in memory
	 *
	 */
	public function testGetSibling_CommittedNotCommitted()
	{
		$this->object->AddSibling($this->sibling);
		$this->object->Save();
		$this->object->AddSibling($this->sibling2);

		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling att")));
		$this->assertEquals(1, sizeof($siblingList));

		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling2 att")));
		$this->assertEquals(0, sizeof($siblingList));

		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling att"), array("or"), array("attribute", "=", "sibling2 att")));
		$this->assertEquals(1, sizeof($siblingList));
	}

	/**
	 * Calling GetSibling multiple times using different conditions
	 * within the same code block should produce the correct result
	 *
	 */
	public function testGetSibling_DifferentConditions()
	{
		$this->object->AddSibling($this->sibling);
		$this->object->AddSibling($this->sibling2);
		$this->object->Save();
		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling att")));
		$this->assertEquals(1, sizeof($siblingList));
		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling att2")));
		$this->assertEquals(1, sizeof($siblingList));
	}

	/**
	 * Tests DNF
	 *
	 */
	public function testGetSibling_Or()
	{
		$this->object->AddSibling($this->sibling);
		$this->object->AddSibling($this->sibling2);

		$this->object->Save();

		$this->object->siblingList = array();

		$siblingList = $this->object->GetSiblingList(array(array("attribute", "=", "sibling att"),
														array("or"),
														array("attribute", "=", "sibling att2")));
		$this->assertEquals(2, sizeof($siblingList));
	}

	/**
	 * Tests Sorting
	 *
	 */
	public function testGetSibling_Sorting()
	{
		$this->object->AddSibling($this->sibling);
		$newSibling1 = new sibling("efg");
		$newSibling2 = new sibling("abc");
		$newSibling3 = new sibling("d");

		$this->object->AddSibling($newSibling1);
		$this->object->AddSibling($newSibling2);
		$this->object->AddSibling($newSibling3);

		$this->object->Save();

		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)), "attribute", true);

		$this->assertEquals("abc", $siblingList[0]->attribute);
		$this->assertEquals("d", $siblingList[1]->attribute);
		$this->assertEquals("efg", $siblingList[2]->attribute);
		$this->assertEquals("sibling att", $siblingList[3]->attribute);

		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)), "attribute", false);

		$this->assertEquals("sibling att", $siblingList[0]->attribute);
		$this->assertEquals("efg", $siblingList[1]->attribute);
		$this->assertEquals("d", $siblingList[2]->attribute);
		$this->assertEquals("abc", $siblingList[3]->attribute);
	}

	/**
	 * Limit
	 *
	 */
	public function testGetSibling_Limit()
	{
		$this->object->AddSibling($this->sibling);
		$newSibling1 = new sibling("efg");
		$newSibling2 = new sibling("abc");
		$newSibling3 = new sibling("d");

		$this->object->AddSibling($newSibling1);
		$this->object->AddSibling($newSibling2);
		$this->object->AddSibling($newSibling3);

		$this->object->Save();

		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)), "", "", 2);

		$this->assertEquals(2, sizeof($siblingList));
	}

	/**
	 * Sorting plus Limit. This is of particular interest when data is encoded
	 *
	 */
	public function testGetSibling_SortingLimit()
	{
		$this->object->AddSibling($this->sibling);
		$newSibling1 = new sibling("efg");
		$newSibling2 = new sibling("abc");
		$newSibling3 = new sibling("d");

		$this->object->AddSibling($newSibling1);
		$this->object->AddSibling($newSibling2);
		$this->object->AddSibling($newSibling3);

		$this->object->Save();

		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)), "attribute", true, 2);

		$this->assertEquals("abc", $siblingList[0]->attribute);
		$this->assertEquals("d", $siblingList[1]->attribute);

		$siblingList = $this->object->GetSiblingList(array(array("siblingId", ">", 0)), "attribute", false, 2);

		$this->assertEquals("sibling att", $siblingList[0]->attribute);
		$this->assertEquals("efg", $siblingList[1]->attribute);
	}

	/**
	 * Tests if new children are correctly assigned and old children
	 * become orphans
	 *
	 */
	public function testSetChildrenList()
	{
		$someChild1 = new child('somechild1 att');
		$someChild2 = new child('somechild2 att');
		$someChild3 = new child('somechild3 att');
		$someChild4 = new child('somechild4 att');

		$childList1 = array($someChild1, $someChild2);
		$childList2 = array($someChild3, $someChild4);

		foreach ($childList1 as $child)
		{
			$this->object->AddChild($child);
		}

		$this->assertEquals('somechild1 att', $this->object->childList[0]->attribute);
		$this->assertEquals('somechild2 att', $this->object->childList[1]->attribute);
		$this->assertEquals(2, sizeof($this->object->childList));

		$this->object->SetChildList($childList2);
		$this->object->Save();

		$currentChildList = $this->object->GetChildList();

		$this->assertEquals('somechild3 att', $currentChildList[0]->attribute);
		$this->assertEquals('somechild4 att', $currentChildList[1]->attribute);

		$this->assertEquals(2, sizeof($currentChildList));
	}

	/**
	 * Tests if new sibling are correctly assigned and old siblings
	 * become orphans
	 *
	 */
	public function testSetSiblingList()
	{
		$someSibling1 = new sibling('somesibling1 att');
		$someSibling2 = new sibling('somesibling2 att');
		$someSibling3 = new sibling('somesibling3 att');
		$someSibling4 = new sibling('somesibling4 att');

		$siblingList1 = array($someSibling1, $someSibling2);
		$siblingList2 = array($someSibling3, $someSibling4);

		foreach ($siblingList1 as $sibling)
		{
			$this->object->AddSibling($sibling);
		}

		$this->assertEquals('somesibling1 att', $this->object->siblingList[0]->attribute);
		$this->assertEquals('somesibling2 att', $this->object->siblingList[1]->attribute);
		$this->assertEquals(2, sizeof($this->object->siblingList));

		$this->object->SetSiblingList($siblingList2);
		$this->object->Save();

		$currentSiblingList = $this->object->GetSiblingList();

		$this->assertEquals('somesibling3 att', $currentSiblingList[0]->attribute);
		$this->assertEquals('somesibling4 att', $currentSiblingList[1]->attribute);

		$this->assertEquals(2, sizeof($currentSiblingList));
	}

	/**
	 *	Should save parent and children/sibling recursively
	 *
	 */
	public function testSave_Deep()
	{
		$this->parent_->AddObject($this->object);
		$this->object->AddChild($this->child);
		$this->object->AddSibling($this->sibling);
		$this->parent_->Save();

		$someParent = new parent_();
		$parentList = $someParent->GetList(array(array("parent_Id", ">", 0)));
		$this->assertEquals(1, sizeof($parentList));

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(1, sizeof($objectList));

		$someChild = new child();
		$childList = $someChild->GetList(array(array("childId", ">", 0)));
		$this->assertEquals(1, sizeof($childList));

		$someSibling = new sibling();
		$siblingList = $someSibling->GetList(array(array("siblingId", ">", 0)));
		$this->assertEquals(1, sizeof($siblingList));
 	}

	/**
	 * Should delete parent and children/sibling recursivelu
	 *
	 */
	public function testDelete_Deep()
	{
		$this->parent_->AddObject($this->object);
		$this->object->AddChild($this->child);
		$this->object->AddSibling($this->sibling);
		$this->parent_->Save();

		$this->parent_->Delete(true, true);

		$someParent = new parent_();
		$parentList = $someParent->GetList(array(array("parent_Id", ">", 0)));
		$this->assertEquals(0, sizeof($parentList));

		$someObject = new object();
		$objectList = $someObject->GetList(array(array("objectId", ">", 0)));
		$this->assertEquals(0, sizeof($objectList));

		$someChild = new child();
		$childList = $someChild->GetList(array(array("childId", ">", 0)));
		$this->assertEquals(0, sizeof($childList));

		$someSibling = new sibling();
		$siblingList = $someSibling->GetList(array(array("siblingId", ">", 0)));
		$this->assertEquals(0, sizeof($siblingList));
	}
}

// Call RelationsTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "RelationsTest::main")
{
	RelationsTest::main();
}
?>
