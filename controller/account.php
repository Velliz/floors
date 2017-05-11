<?php

namespace controller;

use DateTime;
use model\Operator;
use model\Users;
use pukoframework\auth\Session;
use pukoframework\pte\View;
use pukoframework\Request;

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

        if (Request::IsPost()) {

            $alias = Request::Post('alias', null);
            $fullname = Request::Post('fullname', null);
            $phonenumber = Request::Post('phonenumber', null);
            $firstemail = Request::Post('firstemail', null);
            $secondemail = Request::Post('secondemail', null);
            $birthday = Request::Post('birthday', null);
            $birthday = DateTime::createFromFormat('d-m-Y', $birthday);
            $birthday = $birthday->format('Y-m-d');
            $descriptions = Request::Post('descriptions', null);

            Users::Update(array('id' => $data['id']), array(
                'alias' => $alias,
                'fullname' => $fullname,
                'phonenumber' => $phonenumber,
                'firstemail' => $firstemail,
                'secondemail' => $secondemail,
                'birthday' => $birthday,
                'descriptions' => $descriptions,
            ));

            $this->RedirectTo(BASE_URL . 'account');
        }

        $convert_day = $data['birthday'];
        $convert_day = DateTime::createFromFormat('Y-m-d', $convert_day);
        $data['birthday_formated'] = $convert_day->format('d-m-Y');

        return $data;
    }

    /**
     * #Value title Your Authorization
     * #Value menu_authorization active
     */
    public function authorization()
    {
        $data = Session::Get($this)->GetLoginData();

        return $data;
    }

    /**
     * #Value title Your History
     * #Value menu_history active
     */
    public function history()
    {
        $data = Session::Get($this)->GetLoginData();

        return $data;
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
            return Users::GetID($userAccount[0])[0];
        }
    }
}