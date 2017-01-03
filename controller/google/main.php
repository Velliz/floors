<?php
namespace controller\google;

use Exception;
use Google_Client;
use Google_Service_Oauth2;
use model\Applications;
use model\Broker;
use model\Credentials;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;

class main extends View implements Auth
{

    /**
     * @var Google_Client
     */
    var $client;

    public function __construct()
    {
        session_start();

        //TODO:link to app code
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
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {
        $service = new Google_Service_Oauth2($this->client);

        if (isset($_GET['code'])) {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            header('Location: ' . filter_var(BASE_URL . 'google/callbacks', FILTER_SANITIZE_URL));
            exit;
        }

        $this->client->setAccessToken($_SESSION['access_token']);
        $user = $service->userinfo->get();
        
        if (!Session::Get($this)->Login($user->id, 'credentials', Auth::EXPIRED_1_MONTH)) {
            $userId = Users::Create(array(
                'created' => DBI::NOW(),
                'fullname' => $user->name,
                'firstemail' => $user->email,
                'descriptions' => 'Google Login',
            ));
            Credentials::Create(array(
                'userid' => $userId,
                'type' => 'Google',
                'credentials' => $user->id,
                'created' => DBI::NOW(),
                'profilepic' => (string)$user->picture,
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