<?php
require_once('SpecHelper.php');

$conn = new DBConnector();
$result = $conn->query('SELECT * FROM test');
var_dump($result);
