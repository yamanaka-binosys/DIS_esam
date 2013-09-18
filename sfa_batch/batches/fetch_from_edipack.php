<?php

ini_set('display_errors', 'On');

$ftp_server='10.30.3.22';
$ftp_port='6020';
$ftp_user='ftpuser2';
$ftp_pass='ftp_esam';

$conn_id = ftp_connect($ftp_server, $ftp_port);
if (!$conn_id) { exit('FTPサーバに接続できませんでした。'); }

$login_ret = ftp_login($conn_id, $ftp_user, $ftp_pass);
if (!$login_ret) { exit('FTPサーバにログインできませんでした。'); }

$files = ftp_nlist($conn_id, '.');

foreach($files as $file) {
  $fh = fopen($file, 'w');
  echo "${file}と取得します。";
  if (ftp_fget($conn_id, $fh, $file, FTP_BINARY, 0)) {
    echo "${file}を取得しました。\r\n";
  } else {
    echo "${file}の取得に失敗しました。\r\n";
  }
  fclose($fh);
}

ftp_close($conn_id);
