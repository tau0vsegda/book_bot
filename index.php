<?php

  function sendMessageWithInline($chat_id, $message, $replyMarkup) {
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&reply_markup=' . $replyMarkup);
  }
  function sendMessage($chat_id, $message) {
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
  }




  $access_token = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
  $api = 'https://api.telegram.org/bot' . $access_token;

  $output = json_decode(file_get_contents('php://input'), true);
  $chat_id = $output['message']['chat']['id'];
  $first_name = $output['message']['chat']['first_name'];
  $message = $output['message']['text'];
  $callback_query = $output['callback_query'];
  $manga_id = $callback_query['data'];
  $chat_id_in = $callback_query['message']['chat']['id'];



  if ($message == "/start") {
    sendMessage($chat_id, "You are welcome, " . $first_name . "!\nIf you want to know about this bot write /help");
  } elseif ($message == "/help") {
      sendMessage($chat_id, "If you want to find a manga, just write its name (please try using English characters);\n
If you do not receive a reply for a long time, do not worry, you will receive it anyway");

  } elseif (preg_match("/^[A-Za-z ]*$/", $message)) {

      $words = explode(" ", $message);
      $manga = "~" . $words[0];
    //sendMessage($chat_id, "start of finding with name " . $message . ": first word is " . $manga);
    sendMessage($chat_id, $manga . " first word");
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~noragami",// . $manga,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    if (!$err) {echo $response;}

    curl_close($curl);

    if (!$err) {
      $array = new SimpleXMLElement($response);


      foreach ($array as $key => $value) {

        if ($key == "warning") {
          sendMessage($chat_id, "Not found.");
        } else {

          $mes = array(
            "Name" => "",
            "Summary" => "",
            "Picture" => "",
          );

          $censor = true;

          $wordsConsist = true;
          foreach ($words as $word) {
            if (!preg_match("/" . $word . "/i", $value["name"])) {
              $wordsConsist = false;
              break;
            }
          }

          if ($wordsConsist) {

            foreach ($value as $key1 => $value1) {
              if (($key1 == "info") && ($value1["type"] == "Picture") && (is_object($value1))) {
                foreach ($value1 as $key2 => $value2) {
                  $mes["Picture"] = $value2["src"];
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
          }
          if ($censor) {

            $inline_button = array("text" => "add/change status of manga", "callback_data" => "/" . $value["id"]);
            $inline_keyboard = [[$inline_button]];
            $keyboard = array("inline_keyboard"=>$inline_keyboard);
            $replyMarkup = json_encode($keyboard);
            sendMessageWithInline($chat_id, "hi" . $mes["Name"] . "\n\n" . $mes["Summary"] . "\n\n" . $mes["Picture"], $replyMarkup);
          }
        }
      }
    } else {
      sendMessage($chat_id, "I not get response");

    }
  }
if (preg_match("/^[\/0-9]*$/", $manga_id)) {
  sendMessage($chat_id_in, "когда я буду доработан, я обязательно изменю статус манги");

}

?>
