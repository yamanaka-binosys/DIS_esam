<?php
require_once('SpecHelper.php');

class StackTest extends PHPUnit_Framework_TestCase {

  public function testDBSimpleTest()
  {
    $conn = new DBConnector();
    $result = $conn->query('SELECT * FROM test');
    var_dump($result);

    $result = $conn->query('SELECT COUNT(*) FROM test');
    var_dump($result);

    $result = $conn->query('SELECT COUNT(*) FROM test where id = 3');
    var_dump($result);

    $result = (int)array_pop(array_pop($conn->query('SELECT COUNT(*) FROM test where id = 3')));
    var_dump($result);

    $result = $conn->query("INSERT INTO test (id, name) VALUES (3, 'buzz')");
    var_dump($result);

  }
}
