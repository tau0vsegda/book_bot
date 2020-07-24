<?php
require('messageHandling.php');

function welcomeMessage($chatID)
{
    $status = addUsers($chatID);
    $textMessage = "";
    switch ($status)
    {
        case "new":
            $textMessage = "You are welcome!\nIf you want to know about this bot write /help";
            break;
        case "exist":
            $textMessage = "Why do you write /start? Maybe you need a help? If yes write /help.";
            break;
        case "error":
            $textMessage = "Sorry, I can't work right now. Write me later";
            break;
    }
    sendMessage($chatID, $textMessage);
}

function helpMessage($chatID)
{
    sendMessage($chatID, "If you need help write /help;\nIf you want to find a manga, just write its name (please try using English characters);\nIf you want add or change status of manga, click on button;\nIf you what to see status of all manga, write /status\nIf you don't receive a reply for a long time, do not worry, you will receive it anyway");

}

function statusMessage($chatID)
{
    $textMessage = getStatus($chatID);
    if ($textMessage === null)
    {
        sendMessage($chatID, "You don't add anything manga.");
    }
    else
    {
        sendMessage($chatID, $textMessage);
    }
}

function foundMangaMessage($chatID, $message)
{
    if (preg_match("/^[A-Za-z ]*$/", $message))
    {
 //       deleteFromTable("temp_manga_data", false);
        $words = explode(" ", $message);
        $manga = $words[0];
        $response = getResponse($manga);
        if (!$response["error"] === true)
        {
            $arrayManga = new SimpleXMLElement($response["response"]);
            foreach ($arrayManga as $teg => $mangaParameters)
            {
                $found = false;
                $information = null;
                if ($teg !== "warning")
                {
                    if (WordExist($words, $mangaParameters["name"]))
                    {
                        $databasesUsers = selectFromTable("id", "users", "chat_id = '{$chatID}'");
                        $userID = $databasesUsers[0]["id"];
                        insertIntoTable("temp_manga_data", "user_id = '{$userID}', manga_name = '{$mangaParameters["name"]}', manga_id = '{$mangaParameters["id"]}'");
                        $information = searchParameters($mangaParameters);
                        if ($information !== null)
                        {
                            $found = true;
                        }
                    }
                }
                if ($found)
                {
                    $replyMarkup = inlineKeyboard($mangaParameters["id"]);
                    $textMessage = fromArrayToString($information);
                    sendMessageWithInline($chatID, $textMessage, $replyMarkup);
                }
            }
        }
    }
    else
    {
        sendMessage($chatID, "Incorrect data. Please, using only the characters of the English alphabet.");
    }
}

function addOrUpdateStatusMessage($chatID, $manga, $mangaStatus)
{
    $act = addOrUpdateStatus($chatID, $manga, $mangaStatus);
    if ($act !== "error")
    {
        $textMessage = "I {$act} manga status.";
    }
    else
    {
        $textMessage = "Sorry, I can't work right now. Write me later";
    }
    sendMessage($chatID, $textMessage);
}


function addInLikelyMessage($manga, $mangaStatus, $chatID)
{
    $textMessage = null;
    $act = addInLikely($chatID, $manga);
    switch ($act)
    {
        case "likely":
            $textMessage = "I add manga in likely";
            break;
        case "empty":
            $textMessage = "You don't add manga anything status, so you can't add manga in likely";
            break;
        case "error":
            $textMessage = "Sorry, I can't work right now. Write me later";
            break;
    }
    sendMessage($chatID, $textMessage);
}
