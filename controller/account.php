<?php
namespace controller;

use pukoframework\auth\Auth;
use pukoframework\pte\View;

/**
 * Class account
 * @package controller\account
 *
 * #Master account.html
 * #Auth true
 */
class account extends View implements Auth
{

    /**
     * #Value title Your Profile
     * #Value menu_profile active
     */
    public function profile()
    {

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

    }

    public function Logout()
    {

    }

    public function GetLoginData($id)
    {

    }
}