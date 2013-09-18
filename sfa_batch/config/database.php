<?php

class DatabaseConfig {
  public $config = array(
    'dbname' => 'elleair_test',
    'hostname' => 'localhost',
    'username' => 'postgres',
    'password' => 'elleair',
  );

  public function connection_string() {
    $str = "dbname='" . $this->config['dbname'] . "' "
      . "host='" . $this->config['hostname'] . "' "
      . "user='" . $this->config['username'] . "' "
      . "password='" . $this->config['password'] . "'"
      ;
    return $str;
  }
}
