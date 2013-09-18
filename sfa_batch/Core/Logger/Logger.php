<?php

if (!defined('LOG_WARNING'))
{
  define('LOG_WARNING', 1);
}

if (!defined('LOG_NOTICE'))
{
  define('LOG_NOTICE', 2);
}

if (!defined('LOG_DEBUG'))
{
  define('LOG_DEBUG', 3);
}

if (!defined('LOG_ERR'))
{
  define('LOG_ERR', 4);
}

if (!defined('LOG_INFO'))
{
  define('LOG_INFO', 5);
}

class Logger {

  // ログフォルダのパス
  protected static $path = '';

  // ログを標準出力へ出力するか
  protected static $stdout = false;

  // ログを種類ごとに分けるか
  protected static $separate = false;

  /**
   * ログの書き込み
   *
   * @param string $message 書き込みメッセージ
   * @return integer $level LOG_NOTICE or LOG_WARNING or LOG_DEBUG or LOG_ERR
   */
  public static function write($message, $level = LOG_INFO)
  {
    switch ($level) {
      case LOG_WARNING:
        $type = 'warning';break;
      case LOG_NOTICE:
        $type = 'notice';break;
      case LOG_DEBUG:
        $type = 'debug';break;
      case LOG_ERR:
        $type = 'error';break;
      case LOG_INFO:
        $type = 'info';break;
      default:
        return false;
    }

    $log_message = date('Y-m-d H:i:s') . ' [' . $type . ']: ' . $message . "\n";

    if (self::$separate)
    {
      $filename = self::$path . $type . '.log';
    }
    else
    {
      $filename = self::$path . 'application.log';
    }

    file_put_contents($filename, $log_message, FILE_APPEND);
  }

  /**
   * self::$pathへのアクセサ
   *
   * @param string $p ログフォルダのパス
   * @return void or string
   */
  public static function path($p = null)
  {
    if ($p)
    {
      self::$path = $p;
      return;
    }
    return self::$path;
  }

  /**
   * self::$stdoutへのアクセサ
   *
   * @param boolean $p
   * @return void or boolean
   */
  public static function stdout($p = null)
  {
    if ($p)
    {
      self::$stdout = $p;
    }
    return self::$stdout;
  }

  /**
   * self::$separateへのアクセサ
   *
   * @param boolean $p
   * @return void or boolean
   */
  public static function separate($p = null)
  {
    if ($p)
    {
      self::$separate = $p;
    }
    return self::$separate;
  }

}
