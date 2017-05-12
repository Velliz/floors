<?php
namespace controller\manage;

use Exception;
use pukoframework\auth\Session;
use pukoframework\pte\View;

/**
 * Class authorizations
 *
 * #Master master.html
 */
class authorizations extends View
{

    public function OnInitialize()
    {
        $data = Session::Get($this)->GetLoginData();
        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
        return $data;
    }

}