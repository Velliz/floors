<?php

namespace model;

use pukoframework\pda\DBI;

class Authorization
{
    public static function Create($data)
    {
        return DBI::Prepare('authorization')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('authorization')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `authorization`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM authorization WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByUser($userId)
    {
        return DBI::Prepare("SELECT a.id, a.expired, p.pname 
            FROM authorization a LEFT JOIN permissions p ON (a.permissionid = p.id)
            WHERE (a.userid = @1) AND (a.dflag = 0)")->GetData($userId);
    }
}