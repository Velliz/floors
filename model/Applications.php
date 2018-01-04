<?php
namespace model;

use pukoframework\pda\DBI;

/**
 * Class Applications
 * @package model
 */
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
        return DBI::Prepare("SELECT *, id idapp FROM applications WHERE (dflag = 0)")->GetData();
    }

    public static function CountAll()
    {
        $data = DBI::Prepare("SELECT COUNT(id) counter FROM applications WHERE (dflag = 0)")->FirstRow();
        return $data['counter'];
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM applications WHERE (`id` = @1) AND (dflag = 0) LIMIT 1;")->FirstRow($id);
    }

    public static function GetByToken($token)
    {
        return DBI::Prepare("SELECT * FROM applications WHERE (apptoken = @1) AND (dflag = 0) LIMIT 1;")->FirstRow($token);
    }

    public static function GetUserInApps($app_token, $auth_code)
    {
        return DBI::Prepare("SELECT u.id, u.fullname, u.firstemail, ava.filename
                FROM users u
                LEFT JOIN avatars ava ON (u.id = ava.userid)
                LEFT JOIN authorization a ON (a.userid = u.id)
                LEFT JOIN permissions p ON (a.permissionid = p.id)
                LEFT JOIN applications app ON (app.id = p.appid)
                WHERE (u.dflag = 0) AND (app.apptoken = @1) 
                AND (p.pcode = @2);")->GetData($app_token, $auth_code);
    }
}