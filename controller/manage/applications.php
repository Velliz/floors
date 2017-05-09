<?php
namespace controller\manage;

use Exception;
use model\Broker;
use model\Operator;
use model\Permissions;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class applications
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 */
class applications extends View implements Auth
{

    public function __construct()
    {
        parent::__construct();
        $data = Session::Get($this)->GetLoginData();

        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
    }

    /**
     * @return mixed
     * #Value title Application
     */
    public function main()
    {
        $vars['Applications'] = \model\Applications::GetAll();
        return $vars;
    }

    /**
     * @param $appId
     * @return mixed
     */
    public function detail($appId)
    {
        $vars['Application'][0] = \model\Applications::GetID($appId);
        $vars['title'] = $vars['Application'][0]['appname'];
        $vars['Broker'] = Broker::GetByApp($appId);
        $vars['Permissions'] = Permissions::GetByApp($appId);
        return $vars;
    }

    /**
     * #Value title New Application
     */
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
                'apptoken' => md5($appName . $uri . $identifier),
            );
            \model\Applications::Create($app);

            $this->RedirectTo(BASE_URL . 'applications');
        }
    }

    /**
     * #Value title Edit Application
     * @param $appId
     * @return array
     */
    public function edit($appId)
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $appName = Request::Post('appname', null);
            $appDesc = Request::Post('appdesc', null);
            $ip = Request::Post('ip', null);
            $uri = Request::Post('uri', null);
            $identifier = Request::Post('identifier', null);

            $app = array(
                'muid' => $data['id'],
                'modified' => DBI::NOW(),
                'appname' => $appName,
                'appdesc' => $appDesc,
                'ip' => $ip,
                'uri' => $uri,
                'identifier' => $identifier,
            );

            \model\Applications::Update(array('id' => $appId), $app);

            $this->RedirectTo(BASE_URL . 'applications');
        }

        $vars['Application'][0] = \model\Applications::GetID($appId);
        return $vars;
    }

    /**
     * #Template master false
     * @param $appId
     */
    public function delete($appId)
    {
        \model\Applications::Update(array('id' => $appId),
            array(
                'dflag' => 1,
            )
        );
        $this->RedirectTo(BASE_URL . 'applications');
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
            return Operator::GetID($userAccount[1]);
        } else {
            return Users::GetID($userAccount[0]);
        }
    }
}