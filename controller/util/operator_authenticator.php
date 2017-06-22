<?php

namespace controller\util;

use model\Credentials;
use model\Operator;
use model\Users;
use pukoframework\auth\Auth;

/**
 * Class operator_authenticator
 * @package controller\util
 *
 * for floors account based authentication
 */
class operator_authenticator implements Auth
{

    /**
     * @var operator_authenticator
     */
    static $authenticator;

    public static function Instance()
    {
        if (!self::$authenticator instanceof operator_authenticator) {
            self::$authenticator = new operator_authenticator();
        }
        return self::$authenticator;
    }

    public function Login($username, $password)
    {
        $userAccount = explode('\\', $username);
        if (count($userAccount) == 2) {
            $username = $userAccount[1];
            $roles = $userAccount[0];
            $loginResult = Operator::GetUser($username, $password, $roles);
            return (isset($loginResult['id'])) ? $roles . '\\' . $loginResult['id'] : false;
        } else {
            $password_hash = Credentials::GetPasswordHash($username);
            if (helper::password_verify($password, $password_hash)) {
                $loginResult = Users::GetUser($username, $password_hash);
                return (isset($loginResult['id'])) ? $loginResult['id'] : false;
            } else {
                return false;
            }
        }
    }

    public function Logout()
    {
    }

    public function GetLoginData($id)
    {
        $userAccount = explode('\\', $id);
        if (count($userAccount) == 2) {
            return Operator::GetID($userAccount[1]);
        } else {
            return Users::GetID($userAccount[0]);
        }
    }
}