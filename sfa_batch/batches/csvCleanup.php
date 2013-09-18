<?php
$fp = fopen($argv[1], 'rb');
while (($buf = fgets($fp)) !== false)
{
  echo str_replace("\0", "", $buf);
}
fclose($fp);
