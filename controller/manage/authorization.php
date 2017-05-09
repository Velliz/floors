<?php

namespace controller\manage;

use pukoframework\auth\Auth;
use pukoframework\pte\View;
use model\Operator;

/**
 * Class authorization
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 */
class authorization extends View implements Auth
{
    public function create()
    {
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
            return Operator::GetID($userAccount[1])[0];
        } else {
            return \model\Users::GetID($userAccount[1])[0];
        }
    }
}