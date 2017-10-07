<?php

namespace controller;

use controller\util\helper;
use controller\util\operator_authenticator;
use Exception;
use model\Applications;
use model\Credentials;
use model\Logs;
use model\Operator;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\peh\ValueException;
use pukoframework\pte\View;
use pukoframework\Request;

class setup extends View
{

    /**
     * @var Applications
     */
    var $app;

    public function OnInitialize()
    {
        // TODO: Implement OnInitialize() method.
    }

    /**
     * #Template master false
     */
    public function setup()
    {
        //todo: if user with role admin exist block this function
        $exists = Operator::IsExists();
        if ($exists) {
            throw new Exception('App administrator already configured');
        }
        if (Request::IsPost()) {
            $username = Request::Post('username', null);
            $email = Request::Post('email', null);
            $password = Request::Post('pass', null);
            $confirm = Request::Post('confirm', null);

            $appName = Request::Post('appname', null);
            $appDesc = Request::Post('appdesc', null);
            $ip = Request::Post('ip', null);
            $uri = Request::Post('uri', null);
            $identifier = Request::Post('identifier', null);

            $value_err = new ValueException();
            if (strcasecmp($password, $confirm) != 0) {
                $value_err->Prepare('username', $username);
                $value_err->Prepare('email', $email);
                $value_err->Throws(array(),
                    'Password confirmation missmatch');
            }

            $new_user_id = Operator::Create(array(
                'created' => $this->GetServerDateTime(),
                'firstemail' => $email,
                'username' => $username,
                'password' => md5($password),
                'roles' => 'admin',
            ));

            $app = array(
                'cuid' => $new_user_id,
                'created' => DBI::NOW(),
                'appname' => $appName,
                'appdesc' => $appDesc,
                'ip' => $ip,
                'uri' => $uri,
                'identifier' => $identifier,
                'apptoken' => md5($appName . $uri . $identifier),
            );
            Applications::Create($app);
            $this->app = Applications::GetByToken(md5($appName . $uri . $identifier));

            Session::Get(operator_authenticator::Instance())->Login('admin\\' . $username, $password,
                operator_authenticator::EXPIRED_1_MONTH);

            $this->RedirectTo(BASE_URL);
        }
    }
}