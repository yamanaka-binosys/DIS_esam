<?php
require_once('../Core/Parser/CsvParser.php');

class StackTest extends PHPUnit_Framework_TestCase {

  public function testParserSimpleTest()
  {
    $parser = new CsvParser();
    $result = $parser->open('data/test.csv');

    $this->assertEquals(2, count($result));

    $this->assertEquals(1, $result[0][0]);
    $this->assertEquals('foo', $result[0][1]);

    $this->assertEquals(2, $result[1][0]);
    $this->assertEquals('bar', $result[1][1]);
  }
}
