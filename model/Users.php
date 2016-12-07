<?php
namespace model;

use pukoframework\pda\DBI;

class Users
{
    public static function Create($data)
    {
        return DBI::Prepare('users')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('users')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `users`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `users` WHERE `id` = @1")->GetData($id);
    }
}