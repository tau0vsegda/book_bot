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

function selectFromTable($column, $table, $condition)
{
    if ($condition !== false)
    {
        $command = "SELECT {$column} FROM {$table} WHERE {$condition}";
    }
    else
    {
        $command = "SELECT {$column} FROM {$table}";
    }
    $stm = databaseConnection()->query($command);
    if ($stm !== false)
    {
        return $stm->fetchAll();
    }
    else
    {
        return false;
    }
}

function insertIntoTable($table, $parameters)
{
    $command = "INSERT INTO {$table} SET {$parameters}";
    return databaseConnection()->query($command);
}

function updateTable($table, $parameters, $condition)
{
    $command = "UPDATE {$table} SET {$parameters} WHERE {$condition}";
    return databaseConnection()->query($command);
}

function deleteFromTable($table, $condition)
{
    if ($condition !== false)
    {
        $command = "DELETE FROM {$table} WHERE {$condition}";
    }
    else
    {
        $command = "DELETE FROM {$table}";
    }
    return databaseConnection()->query($command);

}


function addUsers($chatID)
{
    $databases = selectFromTable("chat_id", "users", "chat_id = '{$chatID}'");
    if ($databases !== false)
    {
        if (empty($databases))
        {
            insertIntoTable("users", "chat_id = '{$chatID}'");
            return "new";
        }
        else
        {
            return "exist";
        }
    }
    else
    {
        return "error";
    }
}

function addOrUpdateStatus($chatID, $manga, $mangaStatus)
{
    $databasesUsers = selectFromTable("id", "users", "chat_id = '{$chatID}'");
    if ($databasesUsers !== false)
    {
        $userID = $databasesUsers[0]["id"];
        $databasesManga = selectFromTable("id", "manga", "manga_id = '{$manga}' AND user_id = '{$userID}'");
        if ($databasesManga !== false)
        {
            if (empty($databasesManga))
            {
                $databasesName = selectFromTable("manga_name", "temp_manga_data", "manga_id = '{$manga}'");
                if ($databasesName !== false)
                {
                    $mangaName = $databasesName[0]['manga_name'];
                    insertIntoTable("manga", "manga_id = '{$manga}', status = '{$mangaStatus}', user_id = '{$userID}', manga_name = '{$mangaName}'");
                    return "add";
                    }
            }
            else
            {
                $mangaID = $databasesManga[0]["id"];
                updateTable("manga", "status = '{$mangaStatus}'", "id = '{$mangaID}'");
                return "change";
            }
        }
        else
        {
            return "error";
        }
    }
    else
    {
        return "error";
    }
}

function addInLikely($chatID, $manga)
{
    $databasesUsers = selectFromTable("id", "users", "chat_id = '{$chatID}'");
    if ($databasesUsers !== false)
    {
        $userID = $databasesUsers[0]["id"];
        $databasesManga = selectFromTable("id", "manga", "manga_id = '{$manga}' AND user_id = '{$userID}'");
        if ($databasesManga !== false)
        {
            if (!empty($databasesManga))
            {
                $mangaID = $databasesManga[0]["id"];
                updateTable("manga", "likely = '1'", "id = '{$mangaID}' AND user_id = '{$userID}'");
                return "likely";
            }
            else
            {
                return "empty";
            }
        }
        else
        {
            return "error";
        }
    }
    else
    {
        return "error";
    }
}

function getStatus($chatID)
{
    $textMessage = null;
    $databasesUsers = selectFromTable("id", "users", "chat_id = '{$chatID}'");
    if ($databasesUsers !== false)
    {
        $userID = $databasesUsers[0]["id"];
        $databasesManga = selectFromTable("manga_name, status, likely", "manga", "user_id = '{$userID}'");
        if ($databasesManga !== false)
        {
            if (!empty($databasesManga))
            {
                foreach ($databasesManga as $tableRow)
                {
                    $textMessage = $textMessage . "Name: {$tableRow["manga_name"]}" . "\nStatus: {$tableRow["status"]}";
                    if ($tableRow["likely"] == 1)
                    {
                        $textMessage = $textMessage . " (likely manga)" ."\n\n";
                    }
                    else
                    {
                        $textMessage = $textMessage . "\n\n";
                    }
                }
            }
        }
    }
    return $textMessage;
}


