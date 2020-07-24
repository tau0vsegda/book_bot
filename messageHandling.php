<?php
require('workWithDB.php');

const ACCESS_TOKEN = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
$api = 'https://api.telegram.org/bot' . ACCESS_TOKEN;

function sendMessageWithInline($chat_id, $message, $replyMarkup)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&reply_markup=' . $replyMarkup);
}

function sendMessage($chat_id, $message)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}

function getResponse($manga)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~{$manga}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = array(
        "response" => curl_exec($curl),
        "error" => curl_error($curl),
    );
    curl_close($curl);
    return $response;
}

function WordExist($words, $string)
{
    $wordsExist = true;
    foreach ($words as $word)
    {
        if (!preg_match("/{$word}/i", $string))
        {
            $wordsExist = false;
            break;
        }
    }
    return $wordsExist;
}

function searchParameters($mangaParameters)
{
    $information = array(
        "Name" => "",
        "Author" => "",
        "Summary" => "",
        "Picture" => "",
    );
    $censor = true;
    foreach ($mangaParameters as $teg => $parameter)
    {
        if (($teg == "info") && ($parameter["type"] == "Picture"))
        {
            if (is_object($parameter))
            {
                foreach ($parameter as $key => $value) {
                    $information["Picture"] = $value["src"];
                }
            }
            else
            {
                $information["Picture"] = $parameter["src"];
            }
        }
        elseif (($teg == "info") && ($parameter["type"] == "Main title"))
        {
            $information["Summary"] = $parameter;
        }
        elseif ($teg == "staff")
        {
            foreach ($parameter as $key => $value)
            {
                if ($key == "person")
                {
                    $information["Author"] = $value;
                }
            }
        }
        if (($teg == "info") && ($parameter["type"] == "Genres") && ($parameter == "erotica"))
        {
            $censor = false;
        }
    }
    if (!$censor)
    {
        $information = null;
    }
    return $information;
}


function fromArrayToString($array)
{
    $textMessage = null;
    foreach ($array as $key => $value)
    {
        if ($value !== null)
        {
            $textMessage = $textMessage . "{$key}: {$value}\n";
        }
    }
    return $textMessage;
}

function inlineKeyboard($manga)
{
    $inline_button_want = array("text" => "Want to read", "callback_data" => $manga["id"] . "_want");
    $inline_button_now = array("text" => "Reading now", "callback_data" => $manga["id"] . "_now");
    $inline_button_already = array("text" => "Already read", "callback_data" => $manga["id"] . "_already");
    $inline_button_quit = array("text" => "Quit reading", "callback_data" => $manga["id"] . "_quit");
    $inline_button_likely = array("text" => "Likely", "callback_data" => $manga["id"] . "_likely");
    $inline_keyboard = [[$inline_button_want, $inline_button_now, $inline_button_already, $inline_button_quit, $inline_button_likely]];
    $keyboard = array("inline_keyboard" => $inline_keyboard);
    return json_encode($keyboard);
}



