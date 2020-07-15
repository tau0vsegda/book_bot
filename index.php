<?php
  include('vendor/autoload.php');
  use Telegram\Bot\Api;
  echo "hello";
  $telegram = new Api('1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo');
  $result = $telegram -> getWebhookUpdates();
  $text = $result["message"]["text"];
  $chat_id = $result["message"]["chat"]["id"];
  $name = $result["message"]["from"]["username"];
  $keyboard = [["Последние статьи"],["Картинка"],["Гифка"]];
    if($text){
         if ($text == "/start") {
            $reply = "Добро пожаловать в бота!";
            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
           
        }
    }
?>
