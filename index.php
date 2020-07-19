<?php

function sendMessage($chat_id, $message)
 {
 file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
 }

 $access_token = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
 $api = 'https://api.telegram.org/bot' . $access_token;
 
 
 $output = json_decode(file_get_contents('php://input'), true);
 $chat_id = $output['message']['chat']['id'];
 $first_name = $output['message']['chat']['first_name'];
 $message = $output['message']['text'];
 
 if ($message == '/start') {
  sendMessage($chat_id, 'You are welcome, ' . $first_name . '!');
 }else {


  $curl = curl_init();

  curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~" . $message,
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
  } else {
   echo $err;
  }

  if (!$err) {

   foreach ($array as $key => $value) {

    if ($key == "warning") {
     sendMessage($chat_id, "Not found");
    } else {

     $mes = array(
         "Name" => "",
         "Summary" => "",
         "Picture" => "",
     );

     $censor = true;

     foreach ($value as $key1 => $value1) {
      if (($key1 == "info") && ($value1["type"] == "Picture") && (is_object($value1))) {
       foreach ($value1 as $key2 => $value2) {
        $mes["Picture"] = $value1["src"];
       }
      } elseif (($key1 == "info") && ($value1["type"] == "Picture")) {
       $mes["Picture"] = $value1["src"];
      }
      if (($key1 == "info") && ($value1["type"] == "Main title")) {
       $mes["Name"] = $value1;
      }
      if (($key1 == "info") && ($value1["type"] == "Plot Summary")) {
       $mes["Summary"] = $value1;
      }
      if (($key1 == "info") && ($value1["type"] == "Genres") && ($value1 == "erotica")) {
       $censor = false;
      }
     }
     if ($censor) {
      sendMessage($chat_id, $mes["Name"] . "\n\n" . $mes["Summary"] . "\n\n" . $mes["Picture"]);
     }
    }
   }
  } else {
   sendMessage($chat_id, $err);
  }
 }


/*if ($err) {
 sendMessage($chat_id, "cURL Error #:" . $err);
} else {
 sendMessage($chat_id, $array);
}*/

//sendMessage($chat_id, "cdn.animenewsnetwork.com/thumbnails/max500x600/encyc/A11608-3.jpg");
//sendPhoto($chat_id, "cdn.animenewsnetwork.com/thumbnails/max500x600/encyc/A11608-3.jpg");

sendMessage($chat_id, "я завершил работу");

?>