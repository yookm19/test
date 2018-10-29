<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
							
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$userId = "Udeadbeefdeadbeefdeadbeefdeadbeef";
error_log($userId);
pushTextMessage($bot, $userId, "Hello World!");


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
function pushTextMessage($bot, $userId, $text) {
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