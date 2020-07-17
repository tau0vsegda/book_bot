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


//$client = new http\Client;
//$request = new http\Client\Request;

//$request->setRequestUrl('https://animenewsnetwork.p.rapidapi.com/api.xml');
//$request->setRequestMethod('GET');
//$request->setHeaders(array(
//    'x-rapidapi-host' => 'animenewsnetwork.p.rapidapi.com',
//    'x-rapidapi-key' => 'd4298f655cmsh4a328c353b99739p14ed5ejsnd88aacc8e18c'
//));

//$client->enqueue($request)->send();
//$response = $client->getResponse();
//$preload_text = $response;
//sendMessage($chat_id, $preload_text);

//$client = new http\Client;
//$request = new http\Client\Request;

//$request->setRequestUrl('https://animenewsnetwork.p.rapidapi.com/reports.xml');
//$request->setRequestMethod('GET');
//$request->setQuery(new http\QueryString(array(
//    'nskip' => '50',
//    'nlist' => '50',
//    'id' => '155'
//)));

//$request->setHeaders(array(
//    'x-rapidapi-host' => 'animenewsnetwork.p.rapidapi.com',
//    'x-rapidapi-key' => 'd4298f655cmsh4a328c353b99739p14ed5ejsnd88aacc8e18c'
//));

//$client->enqueue($request)->send();
//$response = $client->getResponse();
//$preload_text = $response;
//sendMessage($chat_id, $preload_text);
?>
