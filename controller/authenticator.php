<?php

namespace controller;

use Exception;
use model\Applications;
use model\Credentials;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class go
 * @package controller
 *
 * #Template master false
 * #Master account.html
 */
class authenticator extends View implements Auth
{

    var $app;

    public function __construct()
    {
        parent::__construct();
        session_start();

        //http://localhost/floors/?sso=b57b22e6deed7ce29d6e08e096ea3180ad13d005
        $sso = Request::Get('sso', null);
        if ($sso != null) {
            Session::Get($this)->PutSession('sso', $sso, Auth::EXPIRED_1_MONTH);
        }

        $ssoCache = Session::Get($this)->GetSession('sso');
        if ($ssoCache == false) {
            throw new Exception('app token not set. set with ' . BASE_URL . '?sso=[YOUR_APP_TOKEN]');
        }
        $this->app = Applications::GetByToken($ssoCache);
        if ($this->app == null) {
            throw new Exception('app specified by token ' . $ssoCache . ' not found on floors server');
        }
    }

    public function main()
    {
        $data = Session::Get($this)->GetLoginData();
        if ($data == false) {
            $this->RedirectTo(BASE_URL);
        }

        $key = hash('sha256', $this->app['apptoken']);
        $iv = substr(hash('sha256', $this->app['identifier']), 0, 16);
        $output = openssl_encrypt(json_encode(
            array(
                'id' => $data['id'],
                'name' => $data['fullname'],
                'email' => $data['firstemail'],
            )
        ), 'AES-256-CBC', $key, 0, $iv);
        $output = base64_encode($output);

        $data['href'] = $this->app['uri'] . '?token=' . $output . '&app=' . $this->app['apptoken'];
        return $data;
    }

    public function Login($username, $password)
    {
        $credentials = array();
        if ($password == 'id') $credentials = Credentials::GetUserID($username);
        if ($password == 'credentials') $credentials = Credentials::GetUserByCredentialsID($username);
        if (sizeof($credentials) == 0) return false;

        $credentials = $credentials[0];
        return $credentials['id'];
    }

    public function Logout()
    {

    }

    public function GetLoginData($id)
    {
        return Credentials::GetUserID($id)[0];
    }
}