<?php
namespace controller\app;

use model\Operator;
use pukoframework\auth\Auth;
use pukoframework\pte\View;

/**
 * Class users
 * @package controller\app
 *
 * #Auth true
 */
class users extends View implements Auth
{

    /**
     * @return mixed
     * #Value title Users
     */
    public function main()
    {
        $vars['Users'] = \model\Users::GetAll();
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