<?php

namespace controller\floors;

use controller\util\helper;
use controller\util\operator_authenticator;
use Exception;
use model\Applications;
use model\Credentials;
use model\Logs;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\peh\ValueException;
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

            $login = Session::Get(operator_authenticator::Instance())->Login($username, $password,
                operator_authenticator::EXPIRED_1_MONTH);

            if ($login) {

                $data = Session::Get(operator_authenticator::Instance())->GetLoginData();

                $agent = $_SERVER['HTTP_USER_AGENT'];
                $remote_ip = $_SERVER['REMOTE_ADDR'];
                $method = $_SERVER['REQUEST_METHOD'];
                $http_status = $_SERVER['REDIRECT_STATUS'];
                $tokens = $this->GetRandomToken(10);

                Session::Get(operator_authenticator::Instance())->PutSession('ft', $tokens,
                    operator_authenticator::EXPIRED_1_MONTH);

                Logs::Create(array(
                    'userid' => $data['id'],
                    'credentialid' => isset($data['credentialid']) ? $data['credentialid'] : 0,
                    'datein' => $this->GetServerDateTime(),
                    'requestmethod' => $method,
                    'action' => 'Login',
                    'ipaddress' => $remote_ip,
                    'useragent' => $agent,
                    'httpstatus' => $http_status,
                    'tokens' => $tokens,
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
    }

    /**
     * #Template master false
     * #Auth false
     */
    public function recovery()
    {
    }

    /**
     * #Template master false
     * #Auth false
     */
    public function register()
    {
        $ssoCache = Session::Get(operator_authenticator::Instance())->GetSession('sso');
        if ($ssoCache == false) {
            $this->RedirectTo(BASE_URL . 'select');
        }
        $this->app = Applications::GetByToken($ssoCache);

        if ($this->app == null) {
            throw new Exception('app specified by token ' . $ssoCache . ' not found on floors server');
        }

        if (Request::IsPost()) {
            $username = Request::Post('username', null);
            $email = Request::Post('email', null);
            $password = Request::Post('pass', null);
            $confirm = Request::Post('confirm', null);

            $value_err = new ValueException();
            if (strcasecmp($password, $confirm) != 0) {
                $value_err->Prepare('username', $username);
                $value_err->Prepare('email', $email);
                $value_err->Throws(array(),
                    'Password confirmation missmatch');
            }

            if (Credentials::IsAliasExists($username)) {
                $value_err->Prepare('username', $username);
                $value_err->Prepare('email', $email);
                $value_err->Throws(array(),
                    'Username already taken. Choose another username.');
            }

            $new_user_id = Users::Create(array(
                'created' => $this->GetServerDateTime(),
                'firstemail' => $email,
                'fullname' => $username,
            ));

            $pass_hash = helper::password_hash($password);

            Credentials::Create(array(
                'userid' => $new_user_id,
                'type' => 'Floors',
                'credentials' => $username,
                'secure' => $pass_hash,
                'created' => $this->GetServerDateTime(),
            ));

            Session::Get(operator_authenticator::Instance())->Login($username, $password,
                operator_authenticator::EXPIRED_1_MONTH);

            $data = Session::Get(operator_authenticator::Instance())->GetLoginData();

            $agent = $_SERVER['HTTP_USER_AGENT'];
            $remote_ip = $_SERVER['REMOTE_ADDR'];
            $method = $_SERVER['REQUEST_METHOD'];
            $http_status = $_SERVER['REDIRECT_STATUS'];
            $tokens = $this->GetRandomToken(10);

            Session::Get(operator_authenticator::Instance())->PutSession('ft', $tokens,
                operator_authenticator::EXPIRED_1_MONTH);

            Logs::Create(array(
                'userid' => $data['id'],
                'credentialid' => 0,
                'datein' => $this->GetServerDateTime(),
                'requestmethod' => $method,
                'action' => 'Login',
                'ipaddress' => $remote_ip,
                'useragent' => $agent,
                'httpstatus' => $http_status,
                'tokens' => $tokens,
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
                $this->RedirectTo($this->app['uri'] . '?secure=' . $output .
                    '&app=' . $this->app['apptoken'] .
                    '&token=' . $tokens);
            }

        }

        $data['appname'] = $this->app['appname'];

        return $data;
    }
}