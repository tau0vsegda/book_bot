<?php

  function sendMessage($chat_id, $message) {
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
  }




  $access_token = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
  $api = 'https://api.telegram.org/bot' . $access_token;

  $output = json_decode(file_get_contents('php://input'), true);
  $chat_id = $output['message']['chat']['id'];
  $first_name = $output['message']['chat']['first_name'];
  $message = $output['message']['text'];

  if ($message == '/start') {
    sendMessage($chat_id, "You are welcome, " . $first_name . "!\nIf you want to know about this bot write /help");
  } elseif (preg_match("/^[1-9A-Za-z ]*$/", $message)) {

      $words = explode(" ", $message);
   //   sendMessage($chat_id, "start of finding");
      $manga = $words[0];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~" . $someManga,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if (!$err) {
      $array = new SimpleXMLElement($response);
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
            sendMessage($chat_id, $mes["Name"] . "\n\n" . $mes["Summary"] . "\n\n" . $mes["Picture"]);
          }
        }
      }
    } else {
      sendMessage($chat_id, "I not get response");
      exit;
    }
  }

?>