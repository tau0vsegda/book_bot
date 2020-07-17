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
 
 $preload_text = $first_name . ', я получил ваше сообщение!';
 sendMessage($chat_id, $preload_text);
 //тестовая строка

//include 'cURL';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://animenewsnetwork.p.rapidapi.com/reports.xml?anime=4658",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "x-rapidapi-host: animenewsnetwork.p.rapidapi.com",
        "x-rapidapi-key: d4298f655cmsh4a328c353b99739p14ed5ejsnd88aacc8e18c"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
 echo "cURL Error #:" . $err;
} else {
 echo $response;
}
?>
