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
    if ($event instanceof \LINE\LINEBot\Event\PostbackEvent) {
      replyTextMessage($bot, $event->getReplyToken(), 'Postback受信「' . $event->getPostbackData() . '」');
      continue;
    }
    if ($event instanceof \LINE\LINEBot\Event\FollowEvent) {
      replyTextMessage($bot, $event->getReplyToken(), "Follow受信\nフォローありがとうございます");
      continue;
    }
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
    // replyStickerMessage($bot, $event->getReplyToken(), 11538, 51626498);

    // 動画を返信
    // replyVideoMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/videos/sample.mp4',
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/videos/sample.jpg');

    // オーディオを返信
    // replyAudioMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/audios/sample2.m4a', 244000);

    // 複数のメッセージを返信
    // replyMultiMessage($bot, $event->getReplyToken(),
    //     new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('返信テスト'),
    //     new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder('https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
    //                                                           'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg'),
    //     new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(11538, 51626498));

    // Buttonテンプレートメッセージを返信
    replyButtonTemplate(
      $bot,
      $event->getReplyToken(),
      '天気のお知らせ - 今日の天気予報',
      'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
      '天気の知らせ',
      '今日の天気予報は晴れ',
      new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder ('明日の天気', 'tomorrow'),
      new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('週末の天気', 'weekend'),
      new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('webで見る', 'https://google.jp')
    );
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

  // 動画返信用関数
  function replyVideoMessage($bot, $replyToken, $originalContentUrl, $previewImageUrl) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\VideoMessageBuilder($originalContentUrl, $previewImageUrl));
    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // オーディオ返信用関数
  function replyAudioMessage($bot, $replyToken, $originalContentUrl, $audioLength) {
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\AudioMessageBuilder($originalContentUrl, $audioLength));
    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // 複数のメッセージ返信用関数
  function replyMultiMessage($bot, $replyToken, ...$msgs) {
    $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
    // メッセージを追加
    foreach($msgs as $value) {
      $builder->add($value);
    }
    $response = $bot->replyMessage($replyToken, $builder);
    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // Buttonテンプレート返信用関数
  function replyButtonTemplate($bot, $replyToken, $alternativeText,
                                      $imageUrl, $title, $text, ...$actions) {
    // アクションを格納する配列
    $actionArray = array();
    // アクションを配列に格納
    foreach($actions as $value) {
      array_push($actionArray, $value);
    }
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
      $alternativeText,
      new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder(
        $title, $text, $imageUrl, $actionArray
      )
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if(!$response->isSucceeded()) {
      error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // Confirmテンプレート返信用関数
  function replyCarouselTemplate($bot, $replyToken, $alternativeText, $columnArray) {
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
      $alternativeText,
      // Carouselテンプレートの引数はダイアログの配列
      new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder ($columnArray)
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if (!$response->isSucceeded()) {
      error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
?>