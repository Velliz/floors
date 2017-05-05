<?php
namespace model;


use pukoframework\pda\DBI;

class Permissions
{
    public static function Create($data)
    {
        return DBI::Prepare('permissions')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('permissions')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM permissions WHERE (dflag = 0)")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM permissions WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByToken($token)
    {
        return DBI::Prepare("SELECT * FROM permissions WHERE (`token` = @1) AND (dflag = 0);")->GetData($token);
    }

    public static function GetByApp($appId)
    {
        return DBI::Prepare("SELECT id, created, pname, pcode, description FROM permissions 
          WHERE (`dflag` = 0) AND (appid = @1);")->GetData($appId);
    }
}