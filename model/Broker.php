<?php

namespace model;

use pukoframework\pda\DBI;

class Broker
{

    public static function Create($data)
    {
        return DBI::Prepare('broker')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('broker')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE (dflag = 0);")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE (dflag = 0) AND (`id` = @1)")->GetData($id);
    }

    public static function GetByApp($appId)
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE (dflag = 0) AND (`appid` = @1)")->GetData($appId);
    }

    public static function GetAppCode($appId, $code)
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE (dflag = 0) AND (`appid` = @1) AND (`code` = @2)")->GetData($appId, $code);
    }

}