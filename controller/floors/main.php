<?php
namespace controller\floors;

use Exception;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;
use pukoframework\Request;

class main extends View implements Auth
{

    /**
     * @var string
     */
    var $client;

    public function __construct()
    {

    }

    /**
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {
        $username = Request::Post('fu', null);
        if ($username == null) {
            throw new Exception('username not defined');
        }
        $password = Request::Post('fp', null);
        if ($password == null) {
            throw new Exception('password not defined');
        }
        Session::Get($this)->Login($username, $password);
    }

    #region auth
    public function Login($username, $password)
    {
        if (substr_count($username, 'operator\\') > 0) {

        }
        $loginResult = UserModel::GetUser($username, $password);
        return (isset($loginResult[0]['ID'])) ? $loginResult[0]['ID'] : false;
    }

    public function Logout()
    {
    }

    public function GetLoginData($id)
    {
        return UserModel::GetUserById($id)[0];
    }
    #end region auth
}