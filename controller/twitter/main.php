<?php

namespace controller\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use controller\util\authenticator;
use Exception;
use model\Applications;
use model\Broker;
use model\Credentials;
use model\Logs;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;

class main extends View
{

    /**
     * @var string
     */
    var $app;

    /**
     * @var TwitterOAuth
     */
    var $tObject;

    /**
     * main constructor.
     */
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
        $tBroker = Broker::GetAppCode($this->app['id'], 'T');
        if ($tBroker == false) {
            throw new Exception('T broker is not set.');
        }
        $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config']);
    }

    /**
     * #Template html false
     * #Template master false
     */
    public function callbacks()
    {

        $update_credential = false;

        $tBroker = Broker::GetAppCode($this->app['id'], 'T');
        if ($tBroker == false) {
            throw new Exception('T broker is not set.');
        }

        if (isset($_SESSION['oauth_token'])) {

            $params = array("oauth_verifier" => $_GET['oauth_verifier'], "oauth_token" => $_GET['oauth_token']);
            $access_token = $this->tObject->oauth("oauth/access_token", $params);

            $this->tObject = new TwitterOAuth($tBroker['brokerid'], $tBroker['config'],
                $access_token['oauth_token'], $access_token['oauth_token_secret']);

            $params = array('include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true');
            $userNode = $this->tObject->get("account/verify_credentials", $params);
            $userNode = (array)$userNode;

            //if user already login and add another credential
            $user_session = Session::Get(authenticator::Instance())->GetLoginData();
            if ($user_session != null) {
                $has_credential = Credentials::GetCredentials($user_session['id'], 'Google');
                if ($has_credential == null) {
                    Credentials::Create(array(
                        'userid' => $user_session['id'],
                        'type' => 'Twitter',
                        'credentials' => $userNode['id_str'],
                        'created' => DBI::NOW(),
                        'profilepic' => (string)$userNode['profile_image_url'],
                    ));
                }
                $update_credential = true;
            }
            //endif user already login and add another credential

            if (!Session::Get(authenticator::Instance())->Login($userNode['id_str'], 'credentials',
                authenticator::EXPIRED_1_MONTH)) {
                $userId = Users::Create(array(
                    'created' => DBI::NOW(),
                    'fullname' => $userNode['name'],
                    'firstemail' => $userNode['email'],
                ));
                Credentials::Create(array(
                    'userid' => $userId,
                    'type' => 'Twitter',
                    'credentials' => $userNode['id_str'],
                    'created' => DBI::NOW(),
                    'profilepic' => (string)$userNode['profile_image_url'],
                ));

                Session::Get(authenticator::Instance())->Login($userId, 'id', authenticator::EXPIRED_1_MONTH);
            };

            $data = Session::Get(authenticator::Instance())->GetLoginData();

            $agent = $_SERVER['HTTP_USER_AGENT'];
            $remote_ip = $_SERVER['REMOTE_ADDR'];
            $method = $_SERVER['REQUEST_METHOD'];
            $http_status = $_SERVER['REDIRECT_STATUS'];

            Logs::Create(array(
                'userid' => $data['id'],
                'credentialid' => 0,
                'datein' => $this->GetServerDateTime(),
                'requestmethod' => $method,
                'action' => 'Login',
                'ipaddress' => $remote_ip,
                'useragent' => $agent,
                'httpstatus' => $http_status
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

            $this->RedirectTo($this->app['uri'] . '?token=' . $output . '&app=' . $this->app['apptoken']);
        }
    }

    public function OnInitialize()
    {
        // TODO: Implement OnInitialize() method.
    }
}