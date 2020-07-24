<?php
require('messageHandling.php');

const DB_DSN = "mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_8869f6e7e3f5fac";
const DB_USER = "b01c10efa93d3a";
const DB_PASSWORD = "363d0b63";

const ACCESS_TOKEN = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
$api = 'https://api.telegram.org/bot' . ACCESS_TOKEN;

$databasesUsers = selectFromTable("chat_id", "users", false);
foreach ($databasesUsers as $user)
{
    $chat_id = $user[0];
    sendMessage($chat_id, "Do you want to know your manga statistic? If yes, you can write /statistic.");
}