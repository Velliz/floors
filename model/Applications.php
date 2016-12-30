<?php
namespace model;

use pukoframework\pda\DBI;

class Applications
{
    public static function Create($data)
    {
        return DBI::Prepare('applications')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('applications')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `applications` WHERE (dflag = 0)")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `applications` WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByToken($token)
    {
        return DBI::Prepare("SELECT * FROM `applications` WHERE (`apptoken` = @1) AND (dflag = 0);")->GetData($token);
    }
}