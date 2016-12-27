<?php
namespace controller\app;

use pukoframework\auth\Auth;
use pukoframework\pte\View;

/**
 * Class broker
 * @package controller\app
 *
 * #Auth true
 */
class broker extends View implements Auth
{

    public function create()
    {

    }

    public function edit()
    {

    }

    public function delete()
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

    public function GetLoginData($id)
    {
        // TODO: Implement GetLoginData() method.
    }
}