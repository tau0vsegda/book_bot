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

//include 'cURL';
$curl = curl_init();

curl_setopt_array($curl, array(
//    CURLOPT_URL => "https://cdn.animenewsnetwork.com/reports.xml?nskip=50&nlist=50&id=155
    CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~god-eater",
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

$preload_text = "";

//foreach ($response as $key => $value) {
// $preload_text = $preload_text . $key . ":\n " . $value;

//}
 echo $response;
if ($err) {
 sendMessage($chat_id, "cURL Error #:" . $err);
} else {
 sendMessage($chat_id, $response);
}

sendMessage($chat_id, "я завершил работу");

?>
