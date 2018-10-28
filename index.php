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

// メッセージを送ってきたユーザーを取得
$json = json_decode(file_get_contents("php://input"),true);
$newMids = array();

// if(!isset($json["result"])){
//   exit(0);
// }

foreach($json["result"] as $result){
  $newmids[] = $result["content"]["from"];
}

$messages = array();
foreach($json["result"] as $result){
  if(!isset($result["content"]["text"])){
    continue;
  }
  if(1 > strlen($result["content"]["text"])){
    continue;
  }
  $messages[] = array(
    "contentType" => 1,
    "text" => $result["content"]["text"],
  );
}

// if(0 == count($messages)){
//   exit(0);
// }

// $newMids[] = $event->getUserId();

// // 新規ユーザーの場合は追加
$mids = array_merge($newMids, $mids);
$mids = array_unique($mids);
file_put_contents($midFile, implode(PHP_EOL, $mids));


// 配列に格納された各イベントをループで処理
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

  // メッセージを全登録ユーザーID宛にプッシュ
  // foreach ($mids as $mid) {
  //   echo $mids;
  //   echo $mid;
  //   // テキストを返信し次のイベントの処理へ
	//   pushTextMessage(array_values($mid), $event->getText());
    
  // }

  $userId = $event->getUserId();
  pushTextMessage($userId, "Hello World!");

  // $body = json_encode(
  //   array(
  //     "to" => array_values($mids),
  //     "toChannel" => 1383378250,
  //     "eventType" => $events,
  //     "content" => array(
  //       "messageNotified" => 0,
  //       "messages" => $messages,
  //     ),
  //   )
  // );

  // $ch = curl_init();
  // curl_setopt($ch, CURLOPT_URL, "https://trial-api.line.me/v1/events");
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  //   "Content-Type: application/json; charset=UTF-8",
  //   "X-Line-ChannelID: 1616806503",
  //   "X-Line-ChannelSecret: 4e84d3f2177af51fdb6d50a64fdfc58f",
  //   "X-Line-Trusted-User-With-ACL: /Syc1LLNgaD9peKYbkWMSKoWZqei8OgEWB25aRS+8tHWZHECOL/qyORPmNlJ+YUodxQMX1Rl6oBwGsTQuA63PsDdfZTPkbTuaieMYVGrxinbzH/yVwCmN7C8HiRCnYwwYS+VINtw5m0g022Rd2PPxAdB04t89/1O/w1cDnyilFU=",
  // ));

  // curl_setopt($ch, CURLOPT_POST, 1);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  // $result = curl_exec($ch);

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

// テキストをプッシュ。引数はLINEBot、返信先、テキスト
function pushTextMessage($userId, $text) {
	// メッセージのプッシュを行いレスポンスを取得
	// TextMessageBuilderの引数はテキスト
	$response = $bot->pushMessage($userId, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

	// レスポンスが異常な場合
	if(!$response->isSucceeded()) {
		//エラー内容を出力
		error_log('Failed! '. $response->getHTTPStatus . ' '. $response->getRawBody());
	}
}


?>