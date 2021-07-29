<?php
  echo "Hello World!";
  // ライブラリ読み込み
  repuire_once __DIR__ . '/vendor/autoload.php';

  // 値の取得
  $inputString = file_get_contents('php://input');
  echo $inputString;
  error_log($inputString);
?>