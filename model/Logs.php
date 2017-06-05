<?php

namespace model;


use pukoframework\pda\DBI;

class Logs
{
    public static function Create($data)
    {
        return DBI::Prepare('logs')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('logs')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `logs`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `logs` WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByUser($user_id)
    {
        return DBI::Prepare("SELECT *, id logsid FROM logs WHERE (userid = 1) ORDER BY datein DESC LIMIT 30;")->GetData($user_id);
    }

    public static function CountAll()
    {
        $data = DBI::Prepare("SELECT COUNT(*) counter FROM `logs`")->FirstRow();
        return $data['counter'];
    }

    public static function ExchangeTokenWithUserID($token)
    {
        $sql = "SELECT userid FROM logs l WHERE (l.tokens = @1) LIMIT 1;";
        return DBI::Prepare($sql)->FirstRow($token)['userid'];
    }
}