<?php

namespace controller\util;

use model\Credentials;
use pukoframework\auth\Auth;

/**
 * Class authentication
 * @package controller\util
 *
 * For social authentication
 */
class authenticator implements Auth
{

    /**
     * @var authenticator
     */
    static $authenticator;

    public static function Instance()
    {
        if (!self::$authenticator instanceof authenticator) {
            self::$authenticator = new authenticator();
        }
        return self::$authenticator;
    }

    public function Login($username, $password)
    {
        $credentials = array();
        if ($password == 'id') $credentials = Credentials::GetUserID($username);
        if ($password == 'credentials') $credentials = Credentials::GetUserByCredentialsID($username);
        if ($credentials == null) {
            return false;
        } else {
            return $credentials['id'];
        }
    }

    public function Logout()
    {

    }

    public function GetLoginData($id)
    {
        return Credentials::GetUserID($id);
    }
}