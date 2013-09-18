<?php
$thisHelper = dirname(__FILE__);
require_once($thisHelper . '/../config/config.php');
require_once($thisHelper . '/../config/database.php');
require_once($thisHelper . '/../Core/Core.php');

Logger::path(ROOT . '/log/');
