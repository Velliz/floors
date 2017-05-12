<?php
namespace controller\manage;

use model\Authorization;
use model\Credentials;
use model\Operator;
use pukoframework\auth\Auth;
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
            return Operator::GetID($userAccount[1])[0];
        } else {
            return \model\Users::GetID($userAccount[1])[0];
        }
    }
}