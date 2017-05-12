<?php

namespace controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;
use Facebook\Facebook;
use Google_Client;
use model\Applications;
use model\Broker;
use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\peh\PukoException;
use pukoframework\peh\ThrowService;
use pukoframework\peh\ThrowView;
use pukoframework\peh\ValueException;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class main
 * @package controller
 *
 * #Master master.html
 */
class main extends View implements Auth
{

    var $app;

    /**
     * @var Facebook
     */
    var $fbObject;

    /**
     * @var Google_Client
     */
    var $client;

    /**
     * @var TwitterOAuth
     */
    var $tObject;

    public function OnInitialize()
    {
        return array();
    }

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

        //setup facebook login SDK
        $fBroker = Broker::GetAppCode($this->app['id'], 'FB');
        if ($fBroker == null) {
            throw new Exception('facebook broker is not registered.');
        }
        $this->fbObject = new Facebook([
            'app_id' => $fBroker['brokerid'],
            'app_secret' => $fBroker['config'],
            'default_graph_version' => $fBroker['version'],
        ]);
        //end setup facebook login SDK

        //setup google login SDK
        $gBroker = Broker::GetAppCode($this->app['id'], 'G');
        if ($gBroker == false) {
            throw new Exception('google broker is not set.');
        }
        $this->client = new Google_Client();
        $this->client->setClientId($gBroker['brokerid']);
        $this->client->setClientSecret($gBroker['config']);
        $this->client->setRedirectUri(BASE_URL . 'google/callbacks');
        $this->client->addScope("email");
        $this->client->addScope("profile");
        //end setup google login SDK

        //setup twitter login SDK
        $tBroker = Broker::GetAppCode($this->app['id'], 'T');
        if (sizeof($tBroker) == 0) {
            throw new Exception('twitter broker is not set.');
        }
        $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config']);
        //end setup twitter login SDK
    }

    /**
     * #Auth true
     * #Value title Beranda
     * #Value menu_beranda active
     */
    public function beranda()
    {
        $data = Session::Get($this)->GetLoginData();

        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }

        $vars['Applications'] = Applications::CountAll();
        $vars['Users'] = Users::CountAll();
        $vars['Login'] = 0;

        return $vars;
    }

    /**
     * #Template html false
     * #Auth true
     */
    public function userlogout()
    {
        Session::Get($this)->Logout();
        $this->RedirectTo(BASE_URL);
    }

    /**
     * #Value title Welcome
     * #Template master false
     */
    public function main()
    {
        $session = Session::Get($this)->GetLoginData();
        if ($session != false) {
            if (isset($session['roles'])) {
                $this->RedirectTo(BASE_URL . 'beranda');
            } else {
                $this->RedirectTo(BASE_URL . 'account');
            }
        }

        //begin facebook LOGIN button
        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile', 'user_friends'];
        $vars['FacebookLoginUrl'] = $helper->getLoginUrl(BASE_URL . 'facebook/callbacks', $permissions);
        //end facebook LOGIN button

        //begin google LOGIN button
        $vars['GoogleLoginUrl'] = $this->client->createAuthUrl();
        //end google LOGIN button

        //begin twitter LOGIN button
        $tCredentials = $this->tObject->oauth('oauth/request_token');
        $_SESSION['oauth_token'] = $tCredentials['oauth_token'];
        $_SESSION['oauth_token_secret'] = $tCredentials['oauth_token_secret'];
        $vars['TwitterLoginUrl'] = $this->tObject->url(
            "oauth/authorize",
            array("oauth_token" => $tCredentials['oauth_token'])
        );
        //end twitter LOGIN button

        $vars['appname'] = $this->app['appname'];
        $vars['uri'] = $this->app['uri'];

        $error = Request::Get('error', null);
        $username = Request::Get('username', '');
        switch ($error) {
            case 'username':
                $value = new ValueException();
                $value->Prepare('username', $username);
                $value->Throws($vars, 'Username harus di isi');
                break;
            case 'password':
                $value = new ValueException();
                $value->Prepare('username', $username);
                $value->Throws($vars, 'Password harus di isi');
                break;
            case 'account':
                $value = new ValueException();
                $value->Prepare('username', $username);
                $value->Throws($vars, 'Akun tidak ditemukan');
                break;
            default:
                break;
        }

        return $vars;
    }

    /**
     * #Template master false
     */
    public function tos()
    {
    }

    /**
     * #Template master false
     */
    public function policy()
    {
    }

    /**
     * #Template master false
     */
    public function recovery()
    {
    }

    /**
     * #Template master false
     */
    public function register()
    {



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

    /*
    //__construct()
    $token = Request::Get('token', null);
    $app = Request::Get('app', null);

    if ($token != null && $app != null) {
        Session::Get($this)->Login($app, $token, Auth::EXPIRED_1_MONTH);
    }

    //Login($app, $token)
    $key = hash('sha256', $app);
    $iv = substr(hash('sha256', 'uwmember'), 0, 16);
    $json = openssl_decrypt(base64_decode($token), 'AES-256-CBC', $key, 0, $iv);
    return $json;

    //GetLoginData($json)
    if ($json != '' || $json != false) {
        return (array)json_decode($json);
    } else {
        return false;
    }
    */

}