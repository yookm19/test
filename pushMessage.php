<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
							
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$userId = 'Udeadbeefdeadbeefdeadbeefdeadbeef';
// error_log($userId);

//To 
$to = 'Udeadbeefdeadbeefdeadbeefdeadbeef'; 
//Channel_Access_Token 
$Channel_Access_Token = getenv('CHANNEL_ACCESS_TOKEN');

$mes = 'テストメッセージです';

$response_format = [ 
"type" => "text", 
"text" => $mes 
];

$post_data = [ 
"to" => $to, 
"messages" => [$response_format] 
];

//post 
$channel = curl_init("https://api.line.me/v2/bot/message/push"); 
curl_setopt($channel, CURLOPT_POST, true); 
curl_setopt($channel, CURLOPT_CUSTOMREQUEST, 'POST'); 
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($post_data)); 
curl_setopt($channel, CURLOPT_HTTPHEADER, array( 
'Content-Type: application/json; charset=UTF-8', 
'Authorization: Bearer ' . $Channel_Access_Token 
));

$result = curl_exec($channel); 
// var_dump($result); 
curl_close($channel); 



//---
//pushTextMessage($bot, $userId, "Hello World!");
// $response = $bot->pushMessage('Udeadbeefdeadbeefdeadbeefdeadbeef', new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("Hello World!"));
// if(!$response->isSucceeded()) {
// 	//エラー内容を出力
// 	error_log('Failed! '. $response->getHTTPStatus . ' '. $response->getRawBody());
// }

?>