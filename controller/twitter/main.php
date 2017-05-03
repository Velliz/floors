<?php
namespace controller\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;
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
     * @var TwitterOAuth
     */
    var $tObject;

    /**
     * main constructor.
     */
    public function __construct()
    {
        session_start();

        $tBroker = Broker::GetAppCode('T');
        if (sizeof($tBroker) == 0) throw new Exception('T broker is not set.');
        else $tBroker = $tBroker[0];

        $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config']);
    }

    /**
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {
        //TODO:link to app code
        $tBroker = Broker::GetAppCode('T');
        if (sizeof($tBroker) == 0) throw new Exception('T broker is not set.');
        else $tBroker = $tBroker[0];

        if (isset($_SESSION['oauth_token'])) {

            $params = array("oauth_verifier" => $_GET['oauth_verifier'], "oauth_token" => $_GET['oauth_token']);
            $access_token = $this->tObject->oauth("oauth/access_token", $params);

            $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config'],
                $access_token['oauth_token'], $access_token['oauth_token_secret']);

            $userNode = $this->tObject->get("account/verify_credentials");

            $userNode = (array)$userNode;

            if (!Session::Get($this)->Login($userNode['id_str'], 'credentials', Auth::EXPIRED_1_MONTH)) {
                $userId = Users::Create(array(
                    'created' => DBI::NOW(),
                    'fullname' => $userNode['name'],
                    'firstemail' => '',
                    'descriptions' => 'Facebook Login',
                ));
                Credentials::Create(array(
                    'userid' => $userId,
                    'type' => 'Facebook',
                    'credentials' => $userNode['id_str'],
                    'created' => DBI::NOW(),
                    'profilepic' => (string)$userNode['profile_image_url'],
                ));

                Session::Get($this)->Login($userId, 'id', Auth::EXPIRED_1_MONTH);
            };

            $data = Session::Get($this)->GetLoginData();

            $appToken = Session::Get($this)->GetSession('sso');
            $appToken = Applications::GetByToken($appToken);
            if (sizeof($appToken) == 0)
                $this->RedirectTo(BASE_URL);

            $appToken = $appToken[0];

            $key = hash('sha256', $appToken['apptoken']);
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
    }

    public function Login($username, $password)
    {
        // TODO: Implement Login() method.
    }

    public function Logout()
    {
        // TODO: Implement Logout() method.
    }

    public function GetLoginData($id)
    {
        // TODO: Implement GetLoginData() method.
    }
}