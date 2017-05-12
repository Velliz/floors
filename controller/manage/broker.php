<?php
namespace controller\manage;

use Exception;
use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class broker
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 * #Value menu_applications active
 */
class broker extends View implements Auth
{

    public function OnInitialize()
    {
        $data = Session::Get($this)->GetLoginData();
        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
        return $data;
    }

    /**
     * @param $appId
     *
     * #Value title New Broker
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

    /**
     * @param $brokerId
     * #Template html false
     */
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
            return Operator::GetID($userAccount[1]);
        } else {
            return Users::GetID($userAccount[1]);
        }
    }
}