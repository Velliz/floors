<?php

namespace controller;

use controller\util\authenticator;
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
class resume extends View
{

    var $app;

    public function __construct()
    {
        parent::__construct();
        session_start();

        //http://localhost/floors/?sso=b57b22e6deed7ce29d6e08e096ea3180ad13d005
        $sso = Request::Get('sso', null);
        if ($sso != null) {
            Session::Get(authenticator::Instance())->PutSession('sso', $sso, Auth::EXPIRED_1_MONTH);
        }

        $ssoCache = Session::Get(authenticator::Instance())->GetSession('sso');
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
        $data = Session::Get(authenticator::Instance())->GetLoginData();
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
        $data['appname'] = $this->app['appname'];
        $data['uri'] = $this->app['uri'];
        return $data;
    }
}