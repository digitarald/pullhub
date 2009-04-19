--TEST--
phpunit FailureTest ../../Samples/BankAccount/FailureTest.php
--FILE--
<?php
$_SERVER['argv'][1] = '--no-configuration';
$_SERVER['argv'][2] = 'FailureTest';
$_SERVER['argv'][3] = dirname(dirname(__FILE__)) . '/_files/FailureTest.php';

require_once dirname(dirname(dirname(__FILE__))) . '/TextUI/Command.php';
PHPUnit_TextUI_Command::main();
?>
--EXPECTF--
PHPUnit %s by Sebastian Bergmann.

FFFFFFFFF

Time: %i seconds

There were 9 failures:

1) testAssertArrayEqualsArray(FailureTest)
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ -1,5 +1,5 @@
 Array
 (
-    [0] => 1
+    [0] => 2
 )
 
%s:%i
%s:%i

2) testAssertIntegerEqualsInteger(FailureTest)
Failed asserting that <integer:2> matches expected <integer:1>.
%s:%i
%s:%i

3) testAssertObjectEqualsObject(FailureTest)
Failed asserting that two objects are equal.
--- Expected
+++ Actual
@@ -1,5 +1,5 @@
 stdClass Object
 (
-    [foo] => bar
+    [bar] => foo
 )
 
%s:%i
%s:%i

4) testAssertNullEqualsString(FailureTest)
Failed asserting that <string:bar> matches expected <null>.
%s:%i
%s:%i

5) testAssertStringEqualsString(FailureTest)
Failed asserting that two strings are equal.
expected string <foo>
difference      <xxx>
got string      <bar>
%s:%i
%s:%i

6) testAssertTextEqualsText(FailureTest)
Failed asserting that two strings are equal.
--- Expected
+++ Actual
@@ -1,3 +1,3 @@
 foo
-bar
+baz
 
%s:%i
%s:%i

7) testAssertTextSameText(FailureTest)
expected string <foo>
difference      <xxx>
got string      <bar>
%s:%i
%s:%i

8) testAssertObjectSameObject(FailureTest)
Failed asserting that two variables reference the same object.
%s:%i
%s:%i

9) testAssertObjectSameNull(FailureTest)
<null> does not match expected type "object".
%s:%i
%s:%i

FAILURES!
Tests: 9, Assertions: 9, Failures: 9.
