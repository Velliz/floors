<?php
namespace controller;

use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class account
 * @package controller\account
 *
 * #Master account.html
 * #Auth true
 */
class account extends View
{

    /**
     * #Value title Your Profile
     * #Value menu_profile active
     */
    public function profile()
    {
        $data = Session::Get($this)->GetLoginData();
        $data['user'] = Users::GetID($data['id']);

        var_dump($data);

        return $data;
    }

    /**
     * #Value title Your Authorization
     * #Value menu_authorization active
     */
    public function authorization()
    {

    }

    /**
     * #Value title Your History
     * #Value menu_history active
     */
    public function history()
    {

    }

    public function Login($username, $password)
    {
        $userAccount = explode('\\', $username);
        if (count($userAccount) == 2) {
            $username = $userAccount[1];
            $roles = $userAccount[0];
            $loginResult = Operator::GetUser($username, $password, $roles);
            return (isset($loginResult[0]['id'])) ? $roles . '\\' . $loginResult[0]['id'] : false;
        } else {
            $loginResult = Users::GetUser($username, $password);
            return (isset($loginResult[0]['id'])) ? $loginResult[0]['id'] : false;
        }
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