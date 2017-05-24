<?php
namespace controller\manage;

use Exception;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class settings
 * @package controller\manage
 *
 * #Auth true
 * #Master admin.html
 * #Value menu_settings active
 */
class settings extends View implements Auth
{

    public function OnInitialize()
    {
        $data = Session::Get($this)->GetLoginData();
        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
        return $data;
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

    /**
     * #Value title Settings
     */
    public function main()
    {

    }

    public function Login($username, $password)
    {
        // TODO: Implement Login() method.
    }

    public function Logout()
    {
        // TODO: Implement Logout() method.
    }
}