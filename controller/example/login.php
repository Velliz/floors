<?php

namespace controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use model\BrokerModel;
use pukoframework\pte\View;
use Facebook\Facebook;

class login extends View
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

        $broker = BrokerModel::GetCode('FB');
        if (sizeof($broker) == 0) throw new \Exception('FB broker is not set.');
        else $broker = $broker[0];

        $this->fbObject = new Facebook([
            'app_id' => $broker['brokerid'],
            'app_secret' => $broker['config'],
            'default_graph_version' => $broker['version'],
        ]);
    }

    public function singlesign()
    {

    }

    /**
     * @throws \Exception
     *
     * #Template html false
     */
    public function facebook()
    {

        $helper = $this->fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'user_about_me', 'public_profile', 'user_hometown', 'user_location', 'user_birthday']; // optional
        $loginUrl = $helper->getLoginUrl(BASE_URL . 'facebook/callbacks', $permissions);

        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }

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

        if (isset($accessToken)) {
            $_SESSION['facebook_access_token'] = (string)$accessToken;
        }

        // Sets the default fallback access token so we don't have to pass it to each request
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

        // OAuth 2.0 client handler
        //$oAuth2Client = $this->fbObject->getOAuth2Client();

        // Exchanges a short-lived access token for a long-lived one
        //$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

        echo 'Logged in as ' . $userNode->getName();
        echo "<br/>";
        echo 'Logged in as ' . $userNode->getId();
        echo "<br/>";
        echo 'Logged in as ' . $userNode->getEmail();
        echo "<br/>";
        echo 'Logged in as ' . $userNode->getHometown();
        echo "<br/>";
        echo 'Logged in as ' . $userNode->getLocation();

        //https://graph.facebook.com/USER_ID/picture?width=WIDTH&height=HEIGHT
    }
}