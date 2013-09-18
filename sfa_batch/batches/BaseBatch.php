<?php

class BaseBatch {

  protected $db = null;
  protected $continue = true;

  public function __construct()
  {
    $this->db = new DBConnector();
  }

  public function run()
  {
  }

  protected function log($message, $level = LOG_INFO)
  {
    $new_message = get_class($this) . ': ' . $message;
    Logger::write($new_message, $level);
  }

  protected function continue_or_end()
  {
    if (!$this->continue)
    {
      $this->log('異常終了', LOG_ERR);
      exit;
    }
  }
}
