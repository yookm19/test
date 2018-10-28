<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
							
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader ::LINE_SIGNATURE];
							
// 署名が正当かチェック。正当であればリクエストをパースし配列へ
// 不正であれば例外の内容を出力
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log('parseEventRequest failed. InvalidSignatureException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log('parseEventRequest failed. UnknownEventTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) { 
  error_log('parseEventRequest failed. UnknownMessageTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log('parseEventRequest failed. InvalidEventRequestException => '.var_export($e, true));
}

$midFile = __DIR__ . "/files/mids";

// midsの中身を読み込み
$mids = explode(PHP_EOL, trim(file_get_contents($midFile)));

// // メッセージを送ってきたユーザーを取得
// $newMids = array();
// $newMids[] = $event->getUserId();

// // 新規ユーザーの場合は追加
// $mids = array_merge($newMids, $mids);
// $mids = array_unique($mids);

// file_put_contents($midFile, implode(",", $mids));


// // 配列に格納された各イベントをループで処理
foreach ($events as $event) {

  // error_log($event->getUserId());

  // MessageEventクラスのインスタンスでなければ処理をスキップ
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }
    
  // MessageEventクラスのインスタンスでなければ処理をスキップ
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non Text message event has come');
    continue;
  }

  replyTextMessage($bot, $event->getReplyToken(), $event->getText());

//   // メッセージを全登録ユーザーID宛にプッシュ
//   foreach ($mids as $mid) {
//     $response = $bot->pushMessage($mid, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($event->getText()));
//     // テキストを返信し次のイベントの処理へ
// 	  replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');
    
//   }

}


// テキストを返信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot, $replyToken, $text) {
	// 返信を行いレスポンスを取得
	// TextMessageBuilderの引数はテキスト
	$response = $bot->replymessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

	// レスポンスが異常な場合
	if(!$response->isSucceeded()) {
		//エラー内容を出力
		error_log('Failed! '. $response->getHTTPStatus . ' '. $response->getRawBody());
	}
}


?>