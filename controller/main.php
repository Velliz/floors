<?php

namespace controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use controller\util\operator_authenticator;
use Exception;
use Facebook\Facebook;
use Google_Client;
use model\Applications;
use model\Broker;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\peh\ValueException;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class main
 * @package controller
 *
 * #Master master.html
 */
class main extends View
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

    public function __construct()
    {
        parent::__construct();
        session_start();

        //http://localhost/floors/?sso=b57b22e6deed7ce29d6e08e096ea3180ad13d005
        $sso = Request::Get('sso', null);
        if ($sso != null) {
            Session::Get(operator_authenticator::Instance())->PutSession('sso', $sso, Auth::EXPIRED_1_MONTH);
        } else {
            $this->RedirectTo(BASE_URL . 'select');
            exit;
        }

        $ssoCache = Session::Get(operator_authenticator::Instance())->GetSession('sso');
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
     * #Value title Welcome
     * #Template master false
     */
    public function main()
    {
        $session = Session::Get(operator_authenticator::Instance())->GetLoginData();
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

    public function OnInitialize()
    {

    }
}