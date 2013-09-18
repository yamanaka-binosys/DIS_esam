<?php
require_once('SpecHelper.php');

class StackTest extends PHPUnit_Framework_TestCase {

  public function testLoggerSimpleTest()
  {
    Logger::write('hello logger');
    Logger::write('hello logger', LOG_NOTICE);
    Logger::write('hello logger', LOG_WARNING);
    Logger::write('hello logger', LOG_DEBUG);
    Logger::write('hello logger', LOG_ERR);
  }
}
