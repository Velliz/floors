<?php
namespace model;

use pukoframework\pda\DBI;

class Operator
{
    public static function Create($data)
    {
        return DBI::Prepare('operator')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('operator')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `operator`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `operator` WHERE (`id` = @1) AND (dflag = 0) LIMIT 1;")->GetData($id);
    }

    public static function GetUser($username, $password, $roles)
    {
        return DBI::Prepare("SELECT * FROM operator WHERE (dflag = 0)
        AND (roles = @1) AND (username = @2) AND (password = @3) LIMIT 1;")->GetData($roles, $username, $password);
    }


}