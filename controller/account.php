<?php

namespace controller;

use Abraham\TwitterOAuth\TwitterOAuth;
use controller\util\authenticator;
use DateTime;
use Exception;
use Facebook\Facebook;
use Google_Client;
use model\Applications;
use model\Authorization;
use model\Broker;
use model\Credentials;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class account
 * @package controller\account
 *
 * #Master account.html
 */
class account extends View
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
    }

    /**
     * #Value title Your Profile
     * #Value menu_profile active
     * #Auth true
     */
    public function profile()
    {
        $data = Session::Get(authenticator::Instance())->GetLoginData();

        if (Request::IsPost()) {

            $alias = Request::Post('alias', null);
            $fullname = Request::Post('fullname', null);
            $phonenumber = Request::Post('phonenumber', null);
            $firstemail = Request::Post('firstemail', null);
            $secondemail = Request::Post('secondemail', null);
            $birthday = Request::Post('birthday', null);
            $birthday = DateTime::createFromFormat('d-m-Y', $birthday);
            $birthday = $birthday->format('Y-m-d');
            $descriptions = Request::Post('descriptions', null);

            Users::Update(array('id' => $data['id']), array(
                'alias' => $alias,
                'fullname' => $fullname,
                'phonenumber' => $phonenumber,
                'firstemail' => $firstemail,
                'secondemail' => $secondemail,
                'birthday' => $birthday,
                'descriptions' => $descriptions,
            ));

            $this->RedirectTo(BASE_URL . 'account');
        }
    }

    /**
     * #Value title Your Authorization
     * #Value menu_authorization active
     * #Auth true
     */
    public function authorization()
    {
        $data = Session::Get(authenticator::Instance())->GetLoginData();

        $data['app'] = Authorization::GetAvailableApplication($data['id']);
        foreach ($data['app'] as $key => $val) {
            $temp = Authorization::GetUserToAppAuthorization($data['id'], $val['id']);
            $data['app'][$key]['permission'] = $temp;
        }

        return $data;
    }

    /**
     * #Template html false
     * #Auth true
     */
    public function userlogout()
    {
        Session::Get(authenticator::Instance())->Logout();
        $this->RedirectTo(BASE_URL);
    }

    /**
     * #Value title Your History
     * #Value menu_history active
     * #Auth true
     */
    public function history()
    {
        $data = Session::Get(authenticator::Instance())->GetLoginData();
        return $data;
    }

    public function OnInitialize()
    {
        $ssoCache = Session::Get(authenticator::Instance())->GetSession('sso');
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

        //begin facebook LOGIN button
        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile', 'user_friends'];
        $data['FacebookLoginUrl'] = $helper->getLoginUrl(BASE_URL . 'facebook/callbacks', $permissions);
        //end facebook LOGIN button

        //begin google LOGIN button
        $data['GoogleLoginUrl'] = $this->client->createAuthUrl();
        //end google LOGIN button

        //begin twitter LOGIN button
        $tCredentials = $this->tObject->oauth('oauth/request_token');
        $_SESSION['oauth_token'] = $tCredentials['oauth_token'];
        $_SESSION['oauth_token_secret'] = $tCredentials['oauth_token_secret'];
        $data['TwitterLoginUrl'] = $this->tObject->url(
            "oauth/authorize",
            array("oauth_token" => $tCredentials['oauth_token'])
        );
        //end twitter LOGIN button

        $data = Session::Get(authenticator::Instance())->GetLoginData();

        $facebook = Credentials::GetCredentials($data['id'], 'Facebook');
        $data['facebook'] = ($facebook == null) ? true : false;
        $data['facebook_id'] = $facebook['id'];

        $google = Credentials::GetCredentials($data['id'], 'Google');
        $data['google'] = ($google == null) ? true : false;
        $data['google_id'] = $google['id'];

        $twitter = Credentials::GetCredentials($data['id'], 'Twitter');
        $data['twitter'] = ($twitter == null) ? true : false;
        $data['twitter_id'] = $twitter['id'];

        $data['credentials'] = Credentials::GetCredentialsByUserID($data['id']);

        if ($data['birthday'] != null) {
            $convert_day = $data['birthday'];
            $convert_day = DateTime::createFromFormat('Y-m-d', $convert_day);
            $data['birthday_formated'] = $convert_day->format('d-m-Y');
        }

        return $data;
    }
}