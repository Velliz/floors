<?php

namespace controller;

use controller\util\authenticator;
use DateTime;
use model\Credentials;
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
        $data = Session::Get(authenticator::Instance())->GetLoginData();

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

        $facebook = Credentials::GetCredentials($data['id'], 'Facebook');
        $data['facebook'] = ($facebook == null) ? true : false;
        $data['facebook_id'] = $facebook['id'];

        $google = Credentials::GetCredentials($data['id'], 'Google');
        $data['google'] = ($google == null) ? true : false;
        $data['google_id'] = $google['id'];

        $twitter = Credentials::GetCredentials($data['id'], 'Twitter');
        $data['twitter'] = ($twitter == null) ? true : false;
        $data['twitter_id'] = $twitter['id'];

        if ($data['birthday'] != null) {
            $convert_day = $data['birthday'];
            $convert_day = DateTime::createFromFormat('Y-m-d', $convert_day);
            $data['birthday_formated'] = $convert_day->format('d-m-Y');
        }

        return $data;
    }

    /**
     * #Value title Your Authorization
     * #Value menu_authorization active
     */
    public function authorization()
    {
        $data = Session::Get(authenticator::Instance())->GetLoginData();

        return $data;
    }

    /**
     * #Template html false
     * #Auth true
     */
    public function userlogout()
    {
        Session::Get(authenticator::Instance())->Logout();
        $this->RedirectTo(BASE_URL);
    }

    /**
     * #Template master false
     */
    public function recovery()
    {
    }

    /**
     * #Template master false
     */
    public function register()
    {

    }

    /**
     * #Value title Your History
     * #Value menu_history active
     */
    public function history()
    {
        $data = Session::Get(authenticator::Instance())->GetLoginData();

        return $data;
    }
}