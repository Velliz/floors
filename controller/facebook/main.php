<?php

namespace controller\facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use model\Applications;
use model\BrokerModel;
use model\Credentials;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;

class main extends View implements Auth
{

    /**
     * @var Facebook
     */
    var $fbObject;

    public function __construct()
    {
        session_start();

        $broker = BrokerModel::GetCode('FB');
        if (sizeof($broker) == 0) throw new \Exception('FB broker is not set.');
        else $broker = $broker[0];

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

        if (!Session::Get($this)->Login($userNode->getId(), 'credentials', Auth::EXPIRED_1_MONTH)) {
            $userId = Users::Create(array(
                'created' => DBI::NOW(),
                'fullname' => $userNode->getName(),
                'firstemail' => $userNode->getEmail(),
                'descriptions' => 'Facebook Login',
            ));
            Credentials::Create(array(
                'userid' => $userId,
                'type' => 'Facebook',
                'credentials' => $userNode->getId(),
                'created' => DBI::NOW(),
                'profilepic' => (string)"https://graph.facebook.com/" . $userNode->getId() . "/picture?width=400&height=400",
            ));

            Session::Get($this)->Login($userId, 'id', Auth::EXPIRED_1_MONTH);
        };

        $data = Session::Get($this)->GetLoginData();

        $appToken = Session::Get($this)->GetSession('sso');
        $appToken = Applications::GetByToken($appToken);
        if (sizeof($appToken) == 0)
            $this->RedirectTo(BASE_URL);

        $appToken = $appToken[0];

        $key = hash('sha256', $appToken['token']);
        $iv = substr(hash('sha256', $appToken['identifier']), 0, 16);
        $output = openssl_encrypt(json_encode(
            array(
                'id' => $data['id'],
                'name' => $data['fullname'],
                'email' => $data['firstemail'],
            )
        ), 'AES-256-CBC', $key, 0, $iv);
        $output = base64_encode($output);

        $this->RedirectTo($appToken['uri'] . "?token=" . $output . "&app=" . $appToken['token']);
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
}