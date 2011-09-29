<?php
include 'configuration.php';
include 'objects/class.database.php';
include 'objects/class.object.php';
include 'objects/class.sibling.php';
include 'objects/class.parent_.php';
include 'objects/class.child.php';


$object = new object();
$object->attribute = 'obj att';

$sibling = new sibling('sibling att');
$sibling2 = new sibling('sibling att2');

$child = new child('child att');

$parent = new parent_('parent att');
$parent->Save();

$object->AddChild($child);
$object->AddSibling($sibling);
$object->AddSibling($sibling2);
$object->SetParent_($parent);

$object->Save();

$child->attribute = 'changed att';

$object->Save();



echo "<br/>changing child after it has been assigned a parent";
echo "<pre>";
print_r($object);
echo "</pre>";

echo "saving parent, object, child, sibling<br/>";
echo "<pre>";
print_r($object);
echo "</pre>";

//test to avoid circular dependency
$result = $sibling->AddObject($object);
if ($result === false)
{
	echo "<br/>Did not add object because of circular dependencies";
}
else
{
	echo "<pre>";
	print_r($sibling);
	echo "</pre>";
}


//test to avoid loading same sibling/child multiple times
echo "<br/>printing children list";
echo "<pre>";
$childrenList = $parent->GetObjectList();
print_r($childrenList);
echo "</pre>";

echo "<br/><br/>here we expect the same result as above";
echo "<pre>";
$childrenList2 = $parent->GetObjectList();
print_r($childrenList2);
echo "</pre>";

$id = $object->objectId;
$object = null;
$object = new object();
$object->Get($id);

echo "<br/>printing sibling list";
echo "<pre>";
$siblingList = $object->GetSiblingList(array(array("siblingId", ">", 0)));
foreach ($siblingList as $s)
{
	echo $s->siblingId;
}
print_r($siblingList);
echo "</pre>";

echo "<br/><br/>here we expect the same result as above";
echo "<pre>";
$siblingList2 = $object->GetSiblingList();
print_r($siblingList2);
echo "</pre>";

//setchildlist
$sibling2 = new sibling('new sibling1');
$sibling3 = new sibling('new sibling2');
$sibling4 = new sibling('new sibling3');

$newSiblingList = array($sibling2, $sibling3, $sibling4);


$child2 = new child('new child1');
$child3 = new child('new child2');
$child4 = new child('new child3');

$newChildList = array($child2, $child3, $child4);


$object->SetChildList($newChildList);

$object->SetSiblingList($newSiblingList);

$object->Save();

$object2 = new object();
foreach (array_keys($newChildList) as $key)
{
	$newChild =& $newChildList[$key];
	$object2->AddChild($newChild);
}

//$object2->Save();
echo "<br/>object2 should have 3 children";
echo "<pre>";
print_r($object2);
echo "</pre>";
//setsiblinglist

//testing deletelist
//$object->DeleteList(array(array("attribute", "=", "obj att"), array("objectId", ">", 20)),true );


//essentials
	//save   - ok  (deep) watch out for foreach childrenlist/siblinglist
	//savenew  - ok
	//get  - ok
	//getlist  - ok (multiple getlist should not affect internal arrays)
	//delete - ok (deep) 
	//deletelist (deep)

// parent child
	//addchild	watch out. parent should hold a reference to the object. not a copy
	//setchildlist
	//getchildlist
	//setparent
	//getparent

// sibling
	//addsibling  watch out for circular dependencies (ok) watch out. sibling
// should hold a reference to the object. not a copy
	//getsiblinglist
	//setsiblinglist
?>