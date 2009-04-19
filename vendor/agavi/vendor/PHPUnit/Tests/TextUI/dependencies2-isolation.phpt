--TEST--
phpunit --process-isolation StackTest ../_files/StackTest.php
--FILE--
<?php
$_SERVER['argv'][1] = '--no-configuration';
$_SERVER['argv'][2] = '--process-isolation';
$_SERVER['argv'][3] = 'StackTest';
$_SERVER['argv'][4] = dirname(dirname(__FILE__)) . '/_files/StackTest.php';

require_once dirname(dirname(dirname(__FILE__))) . '/TextUI/Command.php';
PHPUnit_TextUI_Command::main();
?>
--EXPECTF--
PHPUnit %s by Sebastian Bergmann.

..

Time: %i seconds

OK (2 tests, 5 assertions)

