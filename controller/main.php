<?php

namespace controller;

use Exception;
use Facebook\Facebook;
use model\BrokerModel;
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
     * login constructor.
     */
    public function __construct()
    {
        session_start();

        if (isset($_GET['sso'])) Session::Get($this)->PutSession('sso', $_GET['sso'], Auth::EXPIRED_1_WEEK);

        $broker = BrokerModel::GetCode('FB');
        if (sizeof($broker) == 0) throw new Exception('FB broker is not set.');
        else $broker = $broker[0];

        $this->fbObject = new Facebook([
            'app_id' => $broker['brokerid'],
            'app_secret' => $broker['config'],
            'default_graph_version' => $broker['version'],
        ]);
    }

    public function main()
    {
        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'user_about_me', 'public_profile', 'user_hometown', 'user_location', 'user_birthday'];
        $vars['FacebookLoginUrl'] = $helper->getLoginUrl(BASE_URL . 'floors/facebook/callbacks', $permissions);

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