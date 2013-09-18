<?php
if (!defined('CORE')) {
  define('CORE', dirname(__FILE__));
}

require_once(CORE . '/Database/DBConnector.php');
require_once(CORE . '/Parser/CsvParser.php');
require_once(CORE . '/Logger/Logger.php');
