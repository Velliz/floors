<?php
namespace controller\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;
use model\Broker;
use pukoframework\auth\Auth;
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

        $tBroker = Broker::GetCode('T');
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
        $tBroker = Broker::GetCode('T');
        if (sizeof($tBroker) == 0) throw new Exception('T broker is not set.');
        else $tBroker = $tBroker[0];

        if (isset($_SESSION['t_oauth_token'])) {

            $params = array("oauth_verifier" => $_GET['oauth_verifier'], "oauth_token" => $_GET['oauth_token']);
            $access_token = $this->tObject->oauth("oauth/access_token", $params);

            $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config'],
                $access_token['t_oauth_token'], $access_token['t_oauth_token_secret']);

            $content = $this->tObject->get("account/verify_credentials");

            print_r($content);
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