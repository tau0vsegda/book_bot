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
function sendMessage($chat_id, $message)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}

const DB_DSN = "mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_8869f6e7e3f5fac";
const DB_USER = "b01c10efa93d3a";
const DB_PASSWORD = "363d0b63";

const ACCESS_TOKEN = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
$api = 'https://api.telegram.org/bot' . ACCESS_TOKEN;

$stm = databaseConnection()->query("SELECT * FROM users");
$all_users = $stm->fetchAll();
foreach ($all_users as $user) {
    $message = "";
    $user_id = $user["id"];
    $chat_id = $user["chat_id"];
    sendMessage($chat_id, "i get id");
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


?>