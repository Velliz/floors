<?php
namespace controller\google;

use Google_Client;
use Google_Service_Oauth2;
use pukoframework\pte\View;

class main extends View
{

    /**
     * @var Google_Client
     */
    var $client;

    /**
     * main constructor.
     */
    public function __construct()
    {
        $client_id = '1005207926551-mlkrqhc44semjmrh2f5hvi2kudfb773m.apps.googleusercontent.com';
        $client_secret = 'AIzaSyCKb3uol201Ti8hMtcdPPGwDAqLVVl1JUA';

        $this->client = new Google_Client();
        $this->client->setClientId($client_id);
        $this->client->setClientSecret($client_secret);
        $this->client->setRedirectUri(BASE_URL . 'floors/google/callbacks');
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    public function main()
    {
        $service = new Google_Service_Oauth2($this->client);

        if (isset($_GET['code'])) {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            header('Location: ' . filter_var(BASE_URL . 'floors/google/callbacks', FILTER_SANITIZE_URL));
            exit;
        }

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) $this->client->setAccessToken($_SESSION['access_token']);

        $user = $service->userinfo->get();
        echo '<img src="'.$user->picture.'" style="float: right;margin-top: 33px;" />';

        var_dump($user->id, $user->name, $user->email, $user->link, $user->picture);
    }
}