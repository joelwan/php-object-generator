<?php
require_once 'EssentialsTest.php';
require_once 'RelationsTest.php';
require_once 'PHPUnit.php';
 
$suite  = new PHPUnit_TestSuite('EssentialsTest');
$result = PHPUnit::run($suite);
 
print_r($result->toString());

$suite = new PHPUnit_TestSuite('RelationsTest');
$result = PHPUnit::run($suite);

print_r($result->toString());
?>