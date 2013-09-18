<?php
class CsvParser {

  protected $result = array();

  public function __construct(){}

  public function initialize()
  {
    $this->result = array();
  }

  public function open($path)
  {
    $this->initialize();

    $fp = fopen($path, 'r');
    if (!$fp) {
      return false;
    }

    while (($data = fgetcsv($fp)) !== false)
    {
      $this->result[] = $data;
    }

    fclose($fp);

    return $this->result;
  }
}
