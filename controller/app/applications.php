<?php
namespace controller\app;

use pukoframework\auth\Auth;
use pukoframework\pte\View;

class applications extends View implements Auth
{

    /**
     * #Auth true
     */
    public function create()
    {
        $vars['Applications'] = \model\Applications::GetAll();
        return $vars;
    }

    public function Login($username, $password)
    {
        // TODO: Implement Login() method.
    }

    public function Logout()
    {
        // TODO: Implement Logout() method.
    }

    public function GetLoginData($id)
    {
        // TODO: Implement GetLoginData() method.
    }
}