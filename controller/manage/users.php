<?php
namespace controller\manage;

use Exception;
use model\Authorization;
use model\Credentials;
use model\Operator;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class users
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 * #Value menu_users active
 */
class users extends View implements Auth
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
     * @return mixed
     * #Value title User
     */
    public function main()
    {
        $vars['Users'] = \model\Users::GetAll();
        return $vars;
    }

    /**
     * #Value title Detail User
     *
     * @param $userId
     * @return mixed
     */
    public function detail($userId)
    {
        $vars['Users'] = \model\Users::GetID($userId);
        $vars['Credentials'] = Credentials::GetCredentialsByUserID($userId);
        $vars['Authorization'] = Authorization::GetByUser($userId);
        return $vars;
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
            return \model\Users::GetID($userAccount[0]);
        }
    }
}