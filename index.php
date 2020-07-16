<?php
  include('vendor/autoload.php');
//  use Telegram\Bot\Api;
//  echo "hello";
//  $telegram = new Api('1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo');
//  $result = $telegram -> getWebhookUpdates();
//  $text = $result["message"]["text"];
//  $chat_id = $result["message"]["chat"]["id"];
//  $name = $result["message"]["from"]["username"];
//  $keyboard = [["Последние статьи"],["Картинка"],["Гифка"]];
//    if($text){
//         if ($text == "/start") {
//            $reply = "Добро пожаловать в бота!";
//            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
//            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
//           
//        }
//    }
//?>

<?php
 function sendMessage($chat_id, $message) 
 {
 file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
 }
 
 $access_token = 'YOUR TOKEN';
 $api = 'https://api.telegram.org/bot' . $access_token;
 
 
 $output = json_decode(file_get_contents('php://input'), TRUE);
 $chat_id = $output['message']['chat']['id'];
 $first_name = $output['message']['chat']['first_name'];
 $message = $output['message']['text'];
 
 $preload_text = $first_name . ', я получила ваше сообщение!';
 sendMessage($chat_id, $preload_text);
?>
