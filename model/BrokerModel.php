<?php

namespace model;

use pukoframework\pda\DBI;

class BrokerModel
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
        return DBI::Prepare("SELECT * FROM `broker`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE `id` = @1")->GetData($id);
    }

    public static function GetCode($code)
    {
        return DBI::Prepare("SELECT * FROM `broker` WHERE `code` = @1")->GetData($code);
    }

}