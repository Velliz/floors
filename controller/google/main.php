<?php

namespace controller\google;

use controller\util\authenticator;
use Exception;
use Google_Client;
use Google_Service_Oauth2;
use model\Applications;
use model\Broker;
use model\Credentials;
use model\Logs;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;

class main extends View
{

    /**
     * @var string
     */
    var $app;

    /**
     * @var Google_Client
     */
    var $client;

    public function __construct()
    {
        parent::__construct();
        session_start();

        $ssoCache = Session::Get(authenticator::Instance())->GetSession('sso');
        if ($ssoCache == false) {
            throw new Exception('app token not set. set with ' . BASE_URL . '?sso=[YOUR_APP_TOKEN]');
        }
        $this->app = Applications::GetByToken($ssoCache);
        if ($this->app == null) {
            throw new Exception('app specified by token ' . $ssoCache . ' not found on floors server');
        }

        $gBroker = Broker::GetAppCode($this->app['id'], 'G');
        if ($gBroker == false) {
            throw new Exception('G broker is not set.');
        }

        $this->client = new Google_Client();
        $this->client->setClientId($gBroker['brokerid']);
        $this->client->setClientSecret($gBroker['config']);
        $this->client->setRedirectUri(BASE_URL . 'google/callbacks');
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    /**
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {

        $update_credential = false;

        $service = new Google_Service_Oauth2($this->client);

        if (isset($_GET['code'])) {
            $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            header('Location: ' . filter_var(BASE_URL . 'google/callbacks', FILTER_SANITIZE_URL));
            exit;
        }

        $this->client->setAccessToken($_SESSION['access_token']);
        $user = $service->userinfo->get();

        //if user already login and add another credential
        $user_session = Session::Get(authenticator::Instance())->GetLoginData();
        if ($user_session != null) {
            $has_credential = Credentials::GetCredentials($user_session['id'], 'Google');
            if ($has_credential == null) {
                Credentials::Create(array(
                    'userid' => $user_session['id'],
                    'type' => 'Google',
                    'credentials' => $user->id,
                    'created' => DBI::NOW(),
                    'profilepic' => (string)$user->picture,
                ));
            }
            $update_credential = true;
        }
        //endif user already login and add another credential

        if (!Session::Get(authenticator::Instance())->Login($user->id, 'credentials',
            authenticator::EXPIRED_1_MONTH)
        ) {
            $userId = Users::Create(array(
                'created' => DBI::NOW(),
                'fullname' => $user->name,
                'firstemail' => $user->email,
            ));
            Credentials::Create(array(
                'userid' => $userId,
                'type' => 'Google',
                'credentials' => $user->id,
                'created' => DBI::NOW(),
                'profilepic' => (string)$user->picture,
            ));

            Session::Get(authenticator::Instance())->Login($userId, 'id', Auth::EXPIRED_1_MONTH);
        };

        $data = Session::Get(authenticator::Instance())->GetLoginData();

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

        //if already logged in
        if ($update_credential) {
            $this->RedirectTo(BASE_URL . 'account');
        }
        //end if already logged in

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

        $this->RedirectTo($this->app['uri'] . '?token=' . $output . '&app=' . $this->app['apptoken']);
    }

    public function OnInitialize()
    {
        // TODO: Implement OnInitialize() method.
    }
}