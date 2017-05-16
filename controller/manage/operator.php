<?php

namespace controller\manage;

use Exception;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class operator
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 * #Value menu_operators active
 */
class operator extends View implements Auth
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
     * #Value title Operator
     */
    public function main()
    {
        var_dump($_SERVER);
    }

    public function GetLoginData($id)
    {
        $userAccount = explode('\\', $id);
        if (count($userAccount) == 2) {
            return \model\Operator::GetID($userAccount[1]);
        } else {
            return \model\Users::GetID($userAccount[0]);
        }
    }

}