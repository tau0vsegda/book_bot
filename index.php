<?php

header("Content-Type: text/plain");
 function sendMessage($chat_id, $message) 
 {
 file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
 }
 
 $access_token = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
 $api = 'https://api.telegram.org/bot' . $access_token;
 
 
 $output = json_decode(file_get_contents('php://input'), TRUE);
 $chat_id = $output['message']['chat']['id'];
 $first_name = $output['message']['chat']['first_name'];
 $message = $output['message']['text'];
 
 if ($message == '/start') {
  $preload_text = 'You are welcome, ' . $first_name . '!';
 }
 sendMessage($chat_id, $preload_text);
 //тестовая строка

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~god",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


//echo $response;

$xml = simplexml_load_string($response);
$json = json_encode($xml, JSON_UNESCAPED_UNICODE);
//echo $json;
$array = json_decode($json,TRUE);

function test($mas) {
foreach ($mas as $key => $value) {
  echo $key . ":\n";
  if (is_array($value)) {
   test($value);
  } else {
  echo $value . "\n";
 }}}

 test($array);


/*if ($err) {
 sendMessage($chat_id, "cURL Error #:" . $err);
} else {
 sendMessage($chat_id, $array);
}*/

sendMessage($chat_id, "я завершил работу");