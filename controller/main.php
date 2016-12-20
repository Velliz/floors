<?php

namespace controller;

use Exception;
use Facebook\Facebook;
use Google_Client;
use model\Broker;
use model\Credentials;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;

class main extends View implements Auth
{

    /**
     * @var Facebook
     */
    var $fbObject;

    /**
     * @var Google_Client
     */
    var $client;
    
    public function __construct()
    {
        session_start();

        if (isset($_GET['sso'])) Session::Get($this)->PutSession('sso', $_GET['sso'], Auth::EXPIRED_1_WEEK);

        $fBroker = Broker::GetCode('FB');
        if (sizeof($fBroker) == 0) throw new Exception('FB broker is not set.');
        else $fBroker = $fBroker[0];

        $this->fbObject = new Facebook([
            'app_id' => $fBroker['brokerid'],
            'app_secret' => $fBroker['config'],
            'default_graph_version' => $fBroker['version'],
        ]);

        $gBroker = Broker::GetCode('G');
        if (sizeof($gBroker) == 0) throw new Exception('G broker is not set.');
        else $gBroker = $gBroker[0];
        
        $redirect_uri = BASE_URL . 'google/callbacks';

        $this->client = new Google_Client();
        $this->client->setClientId($gBroker['brokerid']);
        $this->client->setClientSecret($gBroker['config']);
        $this->client->setRedirectUri($redirect_uri);
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    /**
     * #Auth true
     */
    public function beranda()
    {

    }
    
    public function profile()
    {
        var_dump(Session::Get($this)->GetLoginData());
    }

    public function main()
    {
        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'user_about_me', 'public_profile', 'user_hometown', 'user_location', 'user_birthday'];
        $vars['FacebookLoginUrl'] = $helper->getLoginUrl(BASE_URL . 'facebook/callbacks', $permissions);

        $vars['GoogleLoginUrl'] = $this->client->createAuthUrl();
        
        return $vars;
    }

    public function Login($username, $password)
    {
        $credentials = Credentials::GetUserID($username);
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