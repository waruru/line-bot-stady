<?php
  // ライブラリ読み込み
  require_once __DIR__ . '/vendor/autoload.php';

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
  function replyConfirmTemplate($bot, $replyToken, $alternativeText, $text, ...$actions) {
    $actionArray = array();
    foreach($actions as $value) {
      array_push($actionArray, $value);
    }
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
      $alternativeText,
      // Confirmテンプレートの引数はテキスト、アクションの配列
      new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder ($text, $actionArray)
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if (!$response->isSucceeded()) {
      error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

  // Carouselテンプレート返信用関数。引数はLINEBot、返信先、代替テキスト、ダイアログの配列
  function replyCarouselTemplate($bot, $replyToken, $alternativeText, $columnArray) {
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
    $alternativeText,
    // Carouselテンプレートの引数はダイアログの配列
    new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder (
      $columnArray)
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if (!$response->isSucceeded()) {
      error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
?>