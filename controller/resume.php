<?php

namespace controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use controller\util\authenticator;
use controller\util\operator_authenticator;
use Exception;
use Facebook\Facebook;
use Google_Client;
use model\Applications;
use model\Broker;
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

    /**
     * @var mixed|null
     */
    var $app;

    /**
     * @var Facebook
     */
    var $fbObject = false;

    /**
     * @var Google_Client
     */
    var $client = false;

    /**
     * @var TwitterOAuth
     */
    var $tObject = false;

    public function __construct()
    {
        parent::__construct();
        session_start();

        //http://localhost/floors/?sso=b57b22e6deed7ce29d6e08e096ea3180ad13d005
        $sso = Request::Get('sso', null);
        if ($sso != null) {
            Session::Get(operator_authenticator::Instance())->PutSession('sso', $sso, Auth::EXPIRED_1_MONTH);
        }

        $ssoCache = Session::Get(operator_authenticator::Instance())->GetSession('sso');
        if ($ssoCache == false) {
            $this->RedirectTo(BASE_URL . 'select');
        }
        $this->app = Applications::GetByToken($ssoCache);
        if ($this->app == null) {
            throw new Exception('app specified by token ' . $ssoCache . ' not found on floors server');
        }

        //setup facebook login SDK
        $fBroker = Broker::GetAppCode($this->app['id'], 'FB');
        if ($fBroker == null) {
            $fBroker = false;
            //throw new Exception('facebook broker is not registered.');
        } else {
            $this->fbObject = new Facebook([
                'app_id' => $fBroker['brokerid'],
                'app_secret' => $fBroker['config'],
                'default_graph_version' => $fBroker['version'],
            ]);
        }

        //end setup facebook login SDK

        //setup google login SDK
        $gBroker = Broker::GetAppCode($this->app['id'], 'G');
        if ($gBroker == false) {
            $gBroker = false;
            //throw new Exception('google broker is not set.');
        } else {
            $this->client = new Google_Client();
            $this->client->setClientId($gBroker['brokerid']);
            $this->client->setClientSecret($gBroker['config']);
            $this->client->setRedirectUri(BASE_URL . 'google/callbacks');
            $this->client->addScope("email");
            $this->client->addScope("profile");
        }
        //end setup google login SDK

        //setup twitter login SDK
        $tBroker = Broker::GetAppCode($this->app['id'], 'T');
        if (sizeof($tBroker) == 0) {
            $this->tObject = false;
            //throw new Exception('twitter broker is not set.');
        } else {
            $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config']);
        }
        //end setup twitter login SDK
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

        $tokens = Session::Get(authenticator::Instance())->GetSession('ft');

        $data['href'] = $this->app['uri'] . '?secure=' . $output .
            '&app=' . $this->app['apptoken'] .
            '&token=' . $tokens;

        $data['appname'] = $this->app['appname'];
        $data['appurl'] = $this->app['uri'];
        return $data;
    }

    public function OnInitialize()
    {
    }
}