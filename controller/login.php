<?php

namespace controller;

use model\BrokerModel;
use pukoframework\pte\View;
use Facebook\Facebook;

class login extends View
{
    public function singlesign()
    {
        
    }

    public function facebook()
    {
        $broker = BrokerModel::GetCode('FB');
        if (sizeof($broker) == 0) throw new \Exception('FB broker is not set.');
        else $broker = $broker[0];

        $fbObject = new Facebook([
            'app_id' => $broker['brokerid'],
            'app_secret' => $broker['config'],
            'default_graph_version' => $broker['version'],
        ]);

        $helper = $fbObject->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes']; // optional
        $loginUrl = $helper->getLoginUrl('http://localhost/floors/fb/callbacks', $permissions);

        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }
    
    public function callbacks()
    {
        
    }
}