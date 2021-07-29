<?php
  // ライブラリ読み込み
  require_once __DIR__ . '/vendor/autoload.php';

  // POST内容表示
  $inputString = file_get_contents('php://input');
  error_log($inputString);

  $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));

  $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

  $signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

  foreach($events as $event) {
    // テキストを返信
    // replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');

    // 画像を返信
    // replyImageMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');

    // 位置情報を返信
    // replyLocationMessage($bot, $event->getReplyToken(), 'CirKit ロゴス',
    //                       '石川県野々市市 金沢工業大学 扇が丘キャンパス',
    //                       36.5308217, 136.6270967);

    // スタンプを返信
    replyStickerMessage($bot, $event->getReplyToken(), 11538, 51626498);
  }

  // テキスト返信用関数
  function replyTextMessage($bot, $replyToken, $text) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // イメージ返信用関数
  function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));

    if(!$response->isSucceeded()) {
      error_log('Failed! '. $response->getHTTPStatus. ' '. $response->getRawBody());
    }
  }

  // 位置情報返信用関数
  function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));

    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
  // スタンプ返信用関数
  function replyStickerMessage($bot, $replyToken, $packageId, $stickerId) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder($packageId, $stickerId));

    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
?>