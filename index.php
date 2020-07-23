<?php

function databaseConnection(): PDO
{
    static $connection = null;
    if ($connection === null)
    {
        $connection = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    }
    return $connection;
}
function sendMessageWithInline($chat_id, $message, $replyMarkup)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&reply_markup=' . $replyMarkup);
}
function sendMessage($chat_id, $message)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}

const DB_DSN = "mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_8869f6e7e3f5fac";
const DB_USER = "b01c10efa93d3a";
const DB_PASSWORD = "363d0b63";

const ACCESS_TOKEN = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
$api = 'https://api.telegram.org/bot' . ACCESS_TOKEN;
$output = json_decode(file_get_contents('php://input'), true);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];
$callback_query = $output['callback_query'];
$inline_message = $callback_query['data'];
$chat_id_in = $callback_query['message']['chat']['id'];

if ($message == "/start")
{
    $stm = databaseConnection()->query("SELECT chat_id FROM users WHERE chat_id = {$chat_id}");
    $databases = $stm->fetchAll();
    if (empty($databases))
    {
        $command = "INSERT INTO users set name = '{$first_name}', chat_id = {$chat_id}";
        $stm = databaseConnection()->query($command);
        sendMessage($chat_id, "You are welcome, " . $first_name . "!\nIf you want to know about this bot write /help");
    }
    else
    {
        sendMessage($chat_id, "Why do you write /start? Maybe you need a help? If yes write /help.");
    }
}
elseif ($message == "/help")
{
    sendMessage($chat_id, "If you want to find a manga, just write its name (please try using English characters);\nIf you do not receive a reply for a long time, do not worry, you will receive it anyway");
}
elseif ($message == "/statistic")
{
    $stm = databaseConnection()->query("SELECT id FROM users");
    $all_users = $stm->fetchAll();
    $message = "";
    $user_id = $all_users[0][0];
    $chat_id = $all_users[0][1];
    $command = "SELECT manga_id, status, likely FROM manga WHERE user_id = '{$user_id}'";
    $stm = databaseConnection()->query($command);
    $all_manga = $stm->fetchAll();
    if (!empty($all_manga))
    {
        foreach ($all_manga as $manga)
        {
            $message = $message . "Manga id: " . $manga["manga_id"] . "\nStatus: ";
            if ($manga["likely"] === 1)
            {
                $message = $message . "(likely manga)\n\n";
            } else {
                $message = $message . "\n\n";
            }
        }
        sendMessage($chat_id, "Statistic:\n\n" . $message);
    }
    else
    {
        sendMessage($chat_id, "now you are not add anything manga");
    }
}
elseif (preg_match("/^[A-Za-z ]*$/", $message))
{
    $words = explode(" ", $message);
    $manga = "~" . $words[0];
    sendMessage($chat_id, $manga);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga={$manga}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    echo $response;
    $err = curl_error($curl);
    curl_close($curl);

    if (!$err)
    {
        $array = new SimpleXMLElement($response);
        foreach ($array as $key => $value)
        {
            if ($key == "warning")
            {
                sendMessage($chat_id, "Not found.");
            }
            else
            {

                $mes = array(
                    "Name" => "",
                    "Summary" => "",
                    "Picture" => "",
                );

                $censor = true;
                $wordsConsist = true;
                foreach ($words as $word)
                {
                    if (!preg_match("/{$word}/i", $value["name"]))
                    {
                        $wordsConsist = false;
                        break;
                    }
                }

                if ($wordsConsist)
                {
                    foreach ($value as $key1 => $value1)
                    {
                        if (($key1 == "info") && ($value1["type"] == "Picture") && (is_object($value1)))
                        {
                            foreach ($value1 as $key2 => $value2)
                            {
                                $mes["Picture"] = $value2["src"];
                            }
                        }
                        elseif (($key1 == "info") && ($value1["type"] == "Picture"))
                        {
                            $mes["Picture"] = $value1["src"];
                        }
                        if (($key1 == "info") && ($value1["type"] == "Main title"))
                        {
                            $mes["Name"] = $value1;
                        }
                        if (($key1 == "info") && ($value1["type"] == "Plot Summary"))
                        {
                            $mes["Summary"] = $value1;
                        }
                        if (($key1 == "info") && ($value1["type"] == "Genres") && ($value1 == "erotica"))
                        {
                            $censor = false;
                        }
                    }
                }
                if ($censor)
                {
                    $inline_button_want = array("text" => "Want to read", "callback_data" => $value["id"] . "_want");
                    $inline_button_now = array("text" => "Reading now", "callback_data" => $value["id"] . "_now");
                    $inline_button_already = array("text" => "Already read", "callback_data" => $value["id"] . "_already");
                    $inline_button_quit = array("text" => "Quit reading", "callback_data" => $value["id"] . "_quit");
                    $inline_button_likely = array("text" => "Likely manga", "callback_data" => $value["id"] . "_likely");
                    $inline_keyboard = [[$inline_button_want, $inline_button_now, $inline_button_already, $inline_button_quit, $inline_button_likely]];
                    $keyboard = array("inline_keyboard" => $inline_keyboard);
                    $replyMarkup = json_encode($keyboard);
                    sendMessageWithInline($chat_id, "{$mes["Name"]}\n\n{$mes["Summary"]}\n\n{$mes["Picture"]}", $replyMarkup);
                }
            }
        }
    }
    else
    {
        sendMessage($chat_id, "I not get response");
    }
}
if (preg_match("/want|now|already|quit/", $inline_message))
{
    $manga_id_status = explode("_", $inline_message);
    $manga_id = $manga_id_status[0];
    $manga_status = $manga_id_status[1];
    $stm_users = databaseConnection()->query("SELECT id FROM users WHERE chat_id = '{$chat_id_in}'");
    $databases_users = $stm_users->fetchAll();
    $user = $databases_users[0]["id"];
    $stm_manga = databaseConnection()->query("SELECT id FROM manga WHERE manga_id = '{$manga_id}' AND user_id = '{$user}'");
    $databases_manga = $stm_manga->fetchAll();
    if (empty($databases_manga))
    {
        $command = "INSERT INTO manga set manga_id = '{$manga_id}', status = '{$manga_status}', likely = '0', user_id = '{$user}'";
        $stm_manga = databaseConnection()->query($command);
        sendMessage($chat_id_in, "You add status \"{$manga_status}\".");
    }
    else
    {
        $manga = $databases_manga[0]["id"];
        $command = "UPDATE manga SET status = '{$manga_status}' WHERE id = '{$manga}'";
        $stm_manga = databaseConnection()->query($command);
        sendMessage($chat_id_in, "You change status on \"{$manga_status}\".");
    }


}
elseif (preg_match("/likely/", $inline_message))
{
    $manga_id_likely = explode("_", $inline_message);
    $manga_id = $manga_id_likely[0];
    $stm_users = databaseConnection()->query("SELECT id FROM users WHERE chat_id = '{$chat_id_in}'");
    $databases_users = $stm_users->fetchAll();
    $user = $databases_users[0]["id"];
    $stm_manga = databaseConnection()->query("SELECT id FROM manga WHERE manga_id = '{$manga_id}' AND user_id = '{$user}'");
    $databases_manga = $stm_manga->fetchAll();
    if (!empty($databases_manga))
    {
        $manga = $databases_manga[0]["id"];
        $command = "UPDATE manga SET likely = '1' WHERE id = '{$manga}'";
        $stm_manga = databaseConnection()->query($command);
        sendMessage($chat_id_in, "You add manga in likely.");
    }
    else
    {
        sendMessage($chat_id_in, "You don't add status of manga, so you can't add manga in likely.");
    }
}

?>