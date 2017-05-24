<?php

namespace controller\manage;

use DateTime;
use Exception;
use model\Operator;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class authorization
 * @package controller\manage
 *
 * #Auth true
 * #Master admin.html
 * #Value menu_users active
 */
class authorization extends View implements Auth
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
     * #Value title Add new Authorization
     * @param null $userTarget
     * @return mixed
     * @throws Exception
     */
    public function create($userTarget = null)
    {
        if ($userTarget == null) {
            throw new Exception('target user not defined.');
        }

        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {

            $appAuth = Request::Post('authorization', null);
            $expired = Request::Post('expired', null);
            $time = Request::Post('time', null);

            $expiredDate = DateTime::createFromFormat('d-m-Y H:i', $expired . ' ' . $time);

            \model\Authorization::Create(array(
                'userid' => $userTarget,
                'permissionid' => $appAuth,
                'created' => DBI::NOW(),
                'cuid' => $data['id'],
                'expired' => $expiredDate->format('Y-m-d H:i')
            ));
            $this->RedirectTo(BASE_URL . 'user/detail/' . $userTarget);
        }

        $data['Application'] = \model\Applications::GetAll();
        return $data;
    }

    /**
     * @param $authId
     * #Template html false
     */
    public function delete($authId)
    {
        \model\Authorization::Update(array('id' => $authId), array('dflag' => 1));
        $this->RedirectTo(BASE_URL . 'users');
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
            return Users::GetID($userAccount[0]);
        }
    }
}