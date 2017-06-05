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
        return DBI::Prepare("SELECT u.*, c.id credentialid FROM credentials c 
        LEFT JOIN users u ON (c.userid = u.id)
        WHERE (c.userid = @1) AND (c.dflag = 0) LIMIT 1;")->FirstRow($id);
    }
    
    public static function GetUserByCredentialsID($credentials)
    {
        return DBI::Prepare("SELECT u.* FROM credentials c 
        LEFT JOIN users u ON (c.userid = u.id)
        WHERE (c.credentials = @1) AND (c.dflag = 0) LIMIT 1;")->FirstRow($credentials);
    }
}