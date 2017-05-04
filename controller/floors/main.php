<?php
namespace controller\floors;

use Exception;
use model\Operator;
use model\Users;
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
        parent::__construct();
        session_start();
    }

    /**
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {
        if (Request::IsPost()) {
            $username = Request::Post('fu', null);
            if ($username == null) {
                throw new Exception('username not defined');
            }
            $password = Request::Post('fp', null);
            if ($password == null) {
                throw new Exception('password not defined');
            }

            $login = Session::Get($this)->Login($username, md5($password), Auth::EXPIRED_1_MONTH);
            if($login) {
                $this->RedirectTo(BASE_URL . 'beranda');
            } else {
                throw new Exception('wrong username or password');
            }
        } else {
            throw new Exception('submitted data error');
        }
    }

    #region auth
    public function Login($username, $password)
    {
        $userAccount = explode('\\', $username);
        if (count($userAccount) == 2) {
            $username = $userAccount[1];
            $roles = $userAccount[0];
            $loginResult = Operator::GetUser($username, $password, $roles);
            return (isset($loginResult[0]['id'])) ? $roles . '\\' . $loginResult[0]['id'] : false;
        } else {
            $loginResult = Users::GetUser($username, $password);
            return (isset($loginResult[0]['id'])) ? $loginResult[0]['id'] : false;
        }
    }

    public function Logout()
    {
    }

    public function GetLoginData($id)
    {
        $userAccount = explode('\\', $id);
        if (count($userAccount) == 2) {
            return Operator::GetID($id)[0];
        } else {
            return Users::GetID($id)[0];
        }
    }
    #end region auth
}