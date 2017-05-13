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
        return DBI::Prepare("SELECT * FROM authorization WHERE (dflag = 0);")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM authorization WHERE (`id` = @1) AND (dflag = 0);")->GetData($id);
    }

    public static function GetByUser($userId)
    {
        return DBI::Prepare("SELECT a.id, a.expired, p.pcode, p.pname
            FROM authorization a LEFT JOIN permissions p ON (a.permissionid = p.id)
            WHERE (a.userid = @1) AND (a.dflag = 0)")->GetData($userId);
    }

    public static function GetAvailableApplication($userId)
    {
        $sql = "SELECT DISTINCT app.id, app.appname
                FROM authorization a
                LEFT JOIN permissions p ON (a.permissionid = p.id)
                LEFT JOIN applications app ON (app.id = p.appid)
                WHERE (a.userid = @1) AND (a.dflag = 0)";
        return DBI::Prepare($sql)->GetData($userId);
    }

    public static function GetUserToAppAuthorization($userId, $appId)
    {
        $sql = "SELECT a.id, a.expired, p.pcode, p.pname
                FROM authorization a
                LEFT JOIN permissions p ON (a.permissionid = p.id)
                LEFT JOIN applications app ON (app.id = p.appid)
                WHERE (a.userid = @1) AND (p.appid = @2) AND (a.dflag = 0)";
        return DBI::Prepare($sql)->GetData($userId, $appId);

    }
}