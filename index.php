<?php
  // ライブラリ読み込み
  require_once __DIR__ . '/vendor/autoload.php';

  $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));

  $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

  $signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

  foreach($events as $event) {
    // テキスト返信
    // replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');

    // 画像返信
    replyImageMessage($bot,
                      $event->getReplyToken(),
                      'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
                      'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');
  }

  function replyTextMessage($bot, $replyToken, $text) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));

    if(!$response->isSucceeded()) {
      error_log('Failed! '. $response->getHTTPStatus. ' '. $response->getRawBody());
    }
  }
?>