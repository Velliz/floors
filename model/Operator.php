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
        return DBI::Prepare("SELECT * FROM `operator` WHERE (dflag = 0)")->GetData();
    }

    public static function GetID($id)
    {
        return DBI::Prepare("SELECT * FROM `operator` WHERE (`id` = @1) AND (dflag = 0) LIMIT 1;")->FirstRow($id);
    }

    /**
     * @param $username
     * @param $password
     * @param $roles
     * @return mixed|null
     *
     * @deprecated changed to bcrypt
     */
    public static function GetUser($username, $password, $roles)
    {
        return DBI::Prepare("SELECT * FROM operator WHERE (dflag = 0)
        AND (roles = @1) AND (username = @2) AND (password = md5(@3)) LIMIT 1;")->FirstRow($roles, $username, $password);
    }

    /**
     * @param $id
     * @param $password
     * @return array
     *
     * @deprecated changed to bcrypt
     */
    public static function GetByIDAndPassword($id, $password)
    {
        return DBI::Prepare("SELECT * FROM operator WHERE (dflag = 0)
        AND (id = @1) AND (password = @2) LIMIT 1;")->GetData($id, $password);
    }

    public static function IsUsernameExists($alias)
    {
        $sql = "SELECT * FROM operator WHERE (username = @1) LIMIT 1";
        $result = DBI::Prepare($sql)->GetData($alias);
        return (count($result) > 0) ? true : false;
    }

    public static function IsExists()
    {
        $sql = "SELECT * FROM operator LIMIT 1";
        $result = DBI::Prepare($sql)->GetData();
        return (count($result) > 0) ? true : false;
    }
}