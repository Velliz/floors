<?php

namespace controller\floors;

use controller\util\operator_authenticator;
use Exception;
use model\Applications;
use model\Logs;
use pukoframework\auth\Session;
use pukoframework\pte\View;
use pukoframework\Request;

class main extends View
{

    /**
     * @var string
     */
    var $app;

    /**
     * @var string
     */
    var $client;

    public function __construct()
    {
        parent::__construct();
        session_start();

        $ssoCache = Session::Get(operator_authenticator::Instance())->GetSession('sso');
        if ($ssoCache == false) {
            throw new Exception('app token not set. set with ' . BASE_URL . '?sso=[YOUR_APP_TOKEN]');
        }
        $this->app = Applications::GetByToken($ssoCache);
        if ($this->app == null) {
            throw new Exception('app specified by token ' . $ssoCache . ' not found on floors server');
        }
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
                $this->RedirectTo(BASE_URL . '?error=username&username=' . $username);
                die();
            }
            $password = Request::Post('fp', null);
            if ($password == null) {
                $this->RedirectTo(BASE_URL . '?error=password&username=' . $username);
                die();
            }

            $login = Session::Get(operator_authenticator::Instance())->Login($username, md5($password),
                operator_authenticator::EXPIRED_1_MONTH);

            if ($login) {

                $data = Session::Get(operator_authenticator::Instance())->GetLoginData();

                $agent = $_SERVER['HTTP_USER_AGENT'];
                $remote_ip = $_SERVER['REMOTE_ADDR'];
                $method = $_SERVER['REQUEST_METHOD'];
                $http_status = $_SERVER['REDIRECT_STATUS'];

                Logs::Create(array(
                    'userid' => $data['id'],
                    'credentialid' => 0,
                    'datein' => $this->GetServerDateTime(),
                    'requestmethod' => $method,
                    'action' => 'Login',
                    'ipaddress' => $remote_ip,
                    'useragent' => $agent,
                    'httpstatus' => $http_status
                ));

                $key = hash('sha256', $this->app['token']);
                $iv = substr(hash('sha256', $this->app['identifier']), 0, 16);
                $output = openssl_encrypt(json_encode(
                    array(
                        'id' => $data['id'],
                        'name' => $data['fullname'],
                        'email' => $data['firstemail'],
                    )
                ), 'AES-256-CBC', $key, 0, $iv);
                $output = base64_encode($output);

                if (stripos($username, "\\") !== false) {
                    $this->RedirectTo(BASE_URL . 'beranda');
                } else {
                    $this->RedirectTo($this->app['uri'] . '?token=' . $output . '&app=' . $this->app['apptoken']);
                }
            } else {
                $this->RedirectTo(BASE_URL . '?error=account&username=' . $username);
                die();
            }
        } else {
            throw new Exception('submitted data error');
        }
    }

    public function OnInitialize()
    {
        // TODO: Implement OnInitialize() method.
    }
}