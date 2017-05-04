<?php
namespace controller\app;

use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class broker
 * @package controller\app
 *
 * #Auth true
 * #Master master.html
 */
class broker extends View implements Auth
{

    /**
     * @param $appId
     */
    public function create($appId)
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $servicename = Request::Post('servicename', null);
            $servicedesc = Request::Post('servicedesc', null);
            $brokerid = Request::Post('brokerid', null);
            $config = Request::Post('config', null);
            $code = Request::Post('code', null);
            $version = Request::Post('version', null);

            $broker = array(
                'cuid' => $data['id'],
                'appid' => $appId,
                'created' => DBI::NOW(),
                'servicename' => $servicename,
                'servicedesc' => $servicedesc,
                'brokerid' => $brokerid,
                'config' => $config,
                'code' => $code,
                'version' => $version,
            );

            \model\Broker::Create($broker);

            $this->RedirectTo(BASE_URL . 'application/detail/' . $appId);
        }
    }

    public function edit($brokerIdSource)
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $servicename = Request::Post('servicename', null);
            $servicedesc = Request::Post('servicedesc', null);
            $brokerid = Request::Post('brokerid', null);
            $config = Request::Post('config', null);
            $code = Request::Post('code', null);
            $version = Request::Post('version', null);

            $broker = array(
                'muid' => $data['id'],
                'modified' => DBI::NOW(),
                'servicename' => $servicename,
                'servicedesc' => $servicedesc,
                'brokerid' => $brokerid,
                'config' => $config,
                'code' => $code,
                'version' => $version,
            );
            \model\Broker::Update(array('id' => $brokerIdSource), $broker);
        }
        $data['Broker'] = \model\Broker::GetID($brokerIdSource);
        return $data;
    }

    public function delete($brokerId)
    {
        \model\Broker::Update(array('id' => $brokerId),
            array(
                'dflag' => 1,
            )
        );
        $this->RedirectTo(BASE_URL . 'applications');
    }

    public function Login($username, $password)
    {
    }

    public function Logout()
    {
    }

    public function GetLoginData($id)
    {
        $userAccount = explode('\\', $id);
        if (count($userAccount) == 2) {
            return Operator::GetID($userAccount[1])[0];
        } else {
            return Users::GetID($userAccount[1])[0];
        }
    }
}