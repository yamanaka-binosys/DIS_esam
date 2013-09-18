<?php

class DBConnector {

  // resource
  protected $conn;
  protected $current_resource;

  public function __construct()
  {
    $this->conn = $this->connect();
    if (!$this->conn)
    {
      return false;
    }
  }

  public function __destruct()
  {
    $this->close($this->conn);
  }

  public function query($sql)
  {
    $result = pg_query($this->conn, $sql);
    $this->current_resource = $result;

    if ($result)
    {
      return pg_fetch_all($result);
    }

    return false;
  }

  protected function connect()
  {
	  if (!class_exists('DatabaseConfig')) {
			throw new Exception('DBヘ接続できません。DatabaseConfigクラスが見つかりません。');
		}
		$config = new DatabaseConfig();
	
    return pg_connect($config->connection_string());
  }

  protected function close()
  {
    return pg_close($this->conn);
  }

  public function result()
  {
    return $this->current_resource;
  }
}
