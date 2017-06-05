<?php

namespace controller\facebook;

use controller\util\authenticator;
use Exception;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use model\Applications;
use model\Broker;
use model\Credentials;
use model\Logs;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;

class main extends View implements Auth
{

    /**
     * @var string
     */
    var $app;

    /**
     * @var Facebook
     */
    var $fbObject;

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

        $broker = Broker::GetAppCode($this->app['id'], 'FB');
        if ($broker == false) {
            throw new Exception('FB broker is not set.');
        }

        $this->fbObject = new Facebook([
            'app_id' => $broker['brokerid'],
            'app_secret' => $broker['config'],
            'default_graph_version' => $broker['version'],
        ]);

        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'user_about_me', 'public_profile', 'user_hometown', 'user_location', 'user_birthday'];
        $vars['FacebookLoginUrl'] = $helper->getLoginUrl(BASE_URL . 'facebook/callbacks', $permissions);
    }

    /**
     * #Template html false
     */
    public function callbacks()
    {

        $update_credential = false;

        $helper = $this->fbObject->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $this->fbObject->setDefaultAccessToken($accessToken);

        try {
            $response = $this->fbObject->get('/me?fields=id,name,email,hometown,location');
            $userNode = $response->getGraphUser();
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        //if user already login and add another credential
        $user_session = Session::Get(authenticator::Instance())->GetLoginData();
        if ($user_session != null) {
            $has_credential = Credentials::GetCredentials($user_session['id'], 'Facebook');
            if ($has_credential == null) {
                Credentials::Create(array(
                    'userid' => $user_session['id'],
                    'type' => 'Facebook',
                    'credentials' => $userNode->getId(),
                    'created' => DBI::NOW(),
                    'profilepic' => (string)"https://graph.facebook.com/" . $userNode->getId() . "/picture?width=400&height=400",
                ));
            }
            $update_credential = true;
        }
        //endif user already login and add another credential

        if (!Session::Get(authenticator::Instance())->Login($userNode->getId(), 'credentials',
            authenticator::EXPIRED_1_MONTH)) {
            $userId = Users::Create(array(
                'created' => DBI::NOW(),
                'fullname' => $userNode->getName(),
                'firstemail' => $userNode->getEmail(),
            ));
            Credentials::Create(array(
                'userid' => $userId,
                'type' => 'Facebook',
                'credentials' => $userNode->getId(),
                'created' => DBI::NOW(),
                'profilepic' => (string)"https://graph.facebook.com/" . $userNode->getId() . "/picture?width=400&height=400",
            ));

            Session::Get(authenticator::Instance())->Login($userId, 'id', Auth::EXPIRED_1_MONTH);
        };

        $data = Session::Get(authenticator::Instance())->GetLoginData();

        $agent = $_SERVER['HTTP_USER_AGENT'];
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $method = $_SERVER['REQUEST_METHOD'];
        $http_status = $_SERVER['REDIRECT_STATUS'];
        $tokens = $this->GetRandomToken(10);

        Session::Get(authenticator::Instance())->PutSession('ft', $tokens, authenticator::EXPIRED_1_MONTH);

        Logs::Create(array(
            'userid' => $data['id'],
            'credentialid' => $data['credentialid'],
            'datein' => $this->GetServerDateTime(),
            'requestmethod' => $method,
            'action' => 'Login',
            'ipaddress' => $remote_ip,
            'useragent' => $agent,
            'httpstatus' => $http_status,
            'tokens' => $tokens,
        ));

        //if already logged in
        if ($update_credential) {
            $this->RedirectTo(BASE_URL . 'account');
        }
        //end if already logged in

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

        $this->RedirectTo($this->app['uri'] . '?secure=' . $output .
            '&app=' . $this->app['apptoken'] .
            '&token=' . $tokens);
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

    public function OnInitialize()
    {

    }
}