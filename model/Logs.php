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

    public static function GetByToken($token)
    {
        return DBI::Prepare("SELECT * FROM `logs` WHERE (`token` = @1) AND (dflag = 0);")->GetData($token);
    }
}