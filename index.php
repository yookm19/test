<?php

// $midFile = dirname(__FILE__)."/mids";
// $json = json_decode(file_get_contents("php://input"), true);
// $mids = explode(PHP_EOL, trim(file_get_contents($midFile)));
// $newMids = array();
// if (!isset($json["result"])) {
//     exit(0);
// }
// foreach ($json["result"] as $result) {
//     $newMids[] = $result["content"]["from"];
// }
// $messages = array();
// foreach ($json["result"] as $result) {
//     if (!isset($result["content"]["text"])) {
//         continue;
//     }
//     if (1 > strlen($result["content"]["text"])) {
//         continue;
//     }
//     $messages[] = array(
//         "contentType" => 1,
//         "text" => $result["content"]["text"],
//     );
// }
// if (0 == count($messages)) {
//     exit(0);
// }
// $mids = array_merge($newMids, $mids);
// $mids = array_unique($mids);
// file_put_contents($midFile, implode(PHP_EOL, $mids));

// $body = json_encode(
// array(
//     "to" => array_values($mids),
//     "toChannel" => 1383378250,
//     "eventType" => "140177271400161403",
//     "content" => array(
//         "messageNotified" => 0,
//         "messages" => $messages,
//     ),
// )
// );
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://trialbot-api.line.me/v1/events");
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// "Content-Type: application/json; charset=UTF-8",
// "X-Line-ChannelID: getenv('CHANNEL_ID')",
// "X-Line-ChannelSecret: getenv('CHANNEL_SECRET')",
// "X-Line-Trusted-User-With-ACL: getenv('CHANNEL_ACCESS_TOKEN')",
// ));
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
// $result = curl_exec($ch);

?>