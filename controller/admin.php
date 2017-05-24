<?php
namespace controller;

use controller\util\operator_authenticator;
use Exception;
use model\Applications;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class admin
 * @package controller
 *
 * #Auth true
 * #Master admin.html
 */
class admin extends View
{
    /**
     * #Value title Beranda
     * #Value menu_beranda active
     */
    public function beranda()
    {
        $data = Session::Get(operator_authenticator::Instance())->GetLoginData();

        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }

        $data['Applications'] = Applications::CountAll();
        $data['Users'] = Users::CountAll();
        $data['Login'] = 0;

        return $data;
    }

    /**
     * #Template html false
     */
    public function userlogout()
    {
        Session::Get(operator_authenticator::Instance())->Logout();
        $this->RedirectTo(BASE_URL);
    }

    public function OnInitialize()
    {
        // TODO: Implement OnInitialize() method.
    }
}