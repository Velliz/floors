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

    public static function GetAll($limit = 50)
    {
        return DBI::Prepare("SELECT * FROM users LIMIT $limit")->GetData();
    }

    public static function CountAll()
    {
        $data = DBI::Prepare("SELECT COUNT(id) counter FROM users WHERE (dflag = 0);")->FirstRow();
        return $data['counter'];
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM users WHERE (id = @1) LIMIT 1")->FirstRow($id);
    }

    public static function GetUserData($id)
    {
        return DBI::Prepare("SELECT id, alias, suffix, fullname, prefix,
        phonenumber, firstemail, secondemail, birthday, descriptions
        FROM users WHERE (id = @1) LIMIT 1")->FirstRow($id);
    }

    public static function GetUser($username, $password)
    {
        return DBI::Prepare("SELECT u.* FROM users u
        LEFT JOIN credentials c ON (u.ID = c.userid)
        WHERE (u.dflag = 0) AND (c.credentials = @1) AND (c.secure = @2)
        LIMIT 1;")->FirstRow($username, $password);
    }

    public static function IsPasswordTrue($userid, $password)
    {
        return DBI::Prepare("SELECT u.* FROM users u
        LEFT JOIN credentials c ON (u.ID = c.userid)
        WHERE (u.dflag = 0) AND (c.type = 'Floors')
        AND (u.id = @1) AND (c.secure = md5(@2)) LIMIT 1;")
            ->FirstRow($userid, $password);
    }
}