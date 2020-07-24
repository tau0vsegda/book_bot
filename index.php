<?php

require('botCommands.php');

$output = json_decode(file_get_contents('php://input'), true);
if (($output !== false) && (!empty($output)))
{
    $chat_id = $output['message']['chat']['id'];
    $message = $output['message']['text'];
    $callback_query = $output['callback_query'];
    if (!empty($callback_query))
    {
        $inline_message = $callback_query['data'];
        $chat_id_in = $callback_query['message']['chat']['id'];
    }
}

switch ($message)
{
    case "/start":
        welcomeMessage($chat_id);
        break;

    case "/help":
        helpMessage($chat_id);
        break;

    case "/status":
        statusMessage($chat_id);
        break;
    default:
        foundMangaMessage($chat_id, $message);
}

if (isset($inline_message))
{
    $messageArray = explode("_", $inline_message);
    $manga_id = $messageArray[0];
    $manga_status = $messageArray[1];
    switch ($manga_status)
    {
        case "likely":
            addInLikelyMessage($manga_id, $manga_status, $chat_id_in);
            break;
        default:
            addOrUpdateStatusMessage($chat_id_in,$manga_id, $manga_status);
    }
}
