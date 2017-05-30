<?php

namespace controller\manage;

use Exception;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\peh\ValueException;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class settings
 * @package controller\manage
 *
 * #Auth true
 * #Master admin.html
 * #Value menu_settings active
 */
class settings extends View implements Auth
{

    public function OnInitialize()
    {
        $data = Session::Get($this)->GetLoginData();
        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
        return $data;
    }

    public function GetLoginData($id)
    {
        $userAccount = explode('\\', $id);
        if (count($userAccount) == 2) {
            return \model\Operator::GetID($userAccount[1]);
        } else {
            return \model\Users::GetID($userAccount[0]);
        }
    }

    /**
     * #Value title Settings
     */
    public function main()
    {
        $data = Session::Get($this)->GetLoginData();
        $action = Request::Post('action', null);
        switch ($action) {
            case 'password':

                $old_password = Request::Post('oldpassword', null);
                $password = Request::Post('password', null);
                $confirm_password = Request::Post('confirmpassword', null);

                $value = new ValueException();
                if (strcmp($password, $confirm_password) != 0) {
                    $value->Prepare('error', 'Password confirmation not match');
                    $value->Throws(array(), "Password confirmation not match");
                }

                $exists = \model\Operator::GetByIDAndPassword($data['id'], md5($old_password));
                if (sizeof($exists) == 0) {
                    $value->Prepare('error', 'Wrong old password');
                    $value->Throws(array(), 'Wrong old password');
                }

                $update = \model\Operator::Update(array('id' => $data['id']), array(
                    'password' => md5($password),
                ));

                if ($update) {
                    $this->RedirectTo(BASE_URL . 'admin/logout');
                } else {
                    $value->Prepare('error', 'Terjadi kesalahan, Silahkan coba beberapa saat lagi');
                    $value->Throws(array(), 'Terjadi kesalahan, Silahkan coba beberapa saat lagi');
                }

                break;
            case 'profile':

                $nama = Request::Post('nama', null);
                if ($nama != null) {
                    \model\Operator::Update(array('id' => $data['id']), array(
                        'fullname' => $nama,
                    ));
                    $this->RedirectTo(BASE_URL . 'settings');
                }

                break;
            default:
                break;
        }
    }

    public function Login($username, $password)
    {
        // TODO: Implement Login() method.
    }

    public function Logout()
    {
        // TODO: Implement Logout() method.
    }
}