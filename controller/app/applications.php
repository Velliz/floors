<?php
namespace controller\app;

use model\Credentials;
use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class applications
 * @package controller\app
 *
 * #Auth true
 */
class applications extends View implements Auth
{

    public function main()
    {
        $vars['Applications'] = \model\Applications::GetAll();
        return $vars;
    }

    public function create()
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $appName = Request::Post('appname', null);
            $appDesc = Request::Post('appdesc', null);
            $ip = Request::Post('ip', null);
            $uri = Request::Post('uri', null);
            $identifier = Request::Post('identifier', null);

            $app = array(
                'cuid' => $data['id'],
                'created' => DBI::NOW(),
                'appname' => $appName,
                'appdesc' => $appDesc,
                'ip' => $ip,
                'uri' => $uri,
                'identifier' => $identifier,
            );
            \model\Applications::Create($app);

            $this->RedirectTo(BASE_URL . 'applications');
        }
    }

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
            return Operator::GetID($userAccount[1])[0];
        } else {
            return Users::GetID($userAccount[1])[0];
        }
    }
}