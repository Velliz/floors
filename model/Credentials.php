<?php
namespace model;

use pukoframework\pda\DBI;

class Credentials
{
    public static function Create($data)
    {
        return DBI::Prepare('credentials')->Save($data);
    }

    public static function Update($where, $data)
    {
        return DBI::Prepare('credentials')->Update($where, $data);
    }

    public static function GetAll()
    {
        return DBI::Prepare("SELECT * FROM `credentials`")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `credentials` WHERE (`id` = @1) AND (dflag = 0);")->FirstRow($id);
    }

    public static function GetCredentials($userId, $type)
    {
        return DBI::Prepare("SELECT c.* FROM credentials c
        WHERE (c.userid = @1) AND (c.type = @2) AND (c.dflag = 0) 
        ORDER BY c.id DESC LIMIT 1;")->FirstRow($userId, $type);
    }

    public static function GetCredentialsByUserID($id)
    {
        return DBI::Prepare("SELECT c.* FROM credentials c
        LEFT JOIN users u ON (c.userid = u.id)
        WHERE (c.userid = @1) AND (c.dflag = 0);")->GetData($id);
    }

    //user have credentials
    public static function GetUserID($id)
    {
        return DBI::Prepare("SELECT u.id, u.alias, DATE_FORMAT(u.created, '%d %M %Y') created, u.modified, u.cuid, 
        u.muid, u.dflag, u.sflag, u.fullname, u.prefix, u.suffix, u.expired, u.phonenumber, u.firstemail,
        u.secondemail, u.birthday, u.descriptions, 
        c.id credentialid 
        FROM credentials c 
        LEFT JOIN users u ON (c.userid = u.id)
        WHERE (c.userid = @1) AND (c.dflag = 0) LIMIT 1;")->FirstRow($id);
    }
    
    public static function GetUserByCredentialsID($credentials)
    {
        return DBI::Prepare("SELECT u.* FROM credentials c 
        LEFT JOIN users u ON (c.userid = u.id)
        WHERE (c.credentials = @1) AND (c.dflag = 0) LIMIT 1;")->FirstRow($credentials);
    }

    public static function GetPasswordHash($alias)
    {
        $sql = "SELECT c.secure FROM users u
        LEFT JOIN credentials c ON (u.ID = c.userid)
        WHERE (u.dflag = 0) AND (c.type = 'Floors')
        AND (c.credentials = @1) LIMIT 1;";
        return DBI::Prepare($sql)->FirstRow($alias)['secure'];
    }

    public static function IsAliasExists($alias)
    {
        $sql = "SELECT credentials FROM credentials WHERE (credentials = @1) AND (type = 'Floors') LIMIT 1";
        $result = DBI::Prepare($sql)->GetData($alias);
        return (count($result) > 0) ? true : false;
    }
}