<?php
namespace model;


use pukoframework\pda\DBI;

class Avatars
{
    public static function Create($data)
    {
        return DBI::Prepare('avatars')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('avatars')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `avatars`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `avatars` WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByToken($token)
    {
        return DBI::Prepare("SELECT * FROM `avatars` WHERE (`token` = @1) AND (dflag = 0);")->GetData($token);
    }
}