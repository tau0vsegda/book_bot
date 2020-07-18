<?php

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
    CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~noragami",
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

if (!$err) {
 $array = new SimpleXMLElement($response);
}
else {echo $err;}


/*function test($mas) {
foreach ($mas as $key => $value) {
  if (is_object($value)) {
   test($value);
  } else {
  echo $value . "\n";
 }}}

  test($array);*/

foreach ($array -> manga as $key => $value) {
 foreach ($value as $key1 => $value1) {
  if (($key1 == "info") && ($value1["type"] == "Picture")) {
   echo "<br><img src=\"" . $value1["src"] . "\">";
  }
  if (($key1 == "info") && ($value1["type"] == "Main title")) {
   echo "<br>name: " . $value1;
  }
  if (($key1 == "info") && ($value1["type"] == "Plot Summary")) {
   echo "<br>summary: " . $value1;
  }
 }
}


/*if ($err) {
 sendMessage($chat_id, "cURL Error #:" . $err);
} else {
 sendMessage($chat_id, $array);
}*/

sendMessage($chat_id, "я завершил работу");