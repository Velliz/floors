<?php

namespace controller\manage;

use Exception;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\peh\ValueException;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class operator
 * @package controller\manage
 *
 * #Auth true
 * #Master admin.html
 * #Value menu_operators active
 */
class operator extends View implements Auth
{

    public function OnInitialize()
    {
        $data = Session::Get($this)->GetLoginData();
        if (!isset($data['roles'])) {
            throw new Exception('access forbidden');
        }
        return $data;
    }

    /**
     * #Value title Operator
     */
    public function main()
    {
        $data['operator'] = \model\Operator::GetAll();
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
     * @param int $operator_id
     * @return mixed
     *
     * #Value title Detail Operator
     */
    public function detail($operator_id = null)
    {
        if ($operator_id == null) {
            $this->RedirectTo(BASE_URL . 'operators');
        }
        $data['operator'][0] = \model\Operator::GetID($operator_id);
        return $data;
    }

    /**
     * #Value title Tambah Operator
     */
    public function addnew()
    {
        if (Request::IsPost()) {
            $fullname = Request::Post('fullname', null);
            $username = Request::Post('username', null);
            $password = Request::Post('password', null);
            $confirm = Request::Post('confirm', null);
            $roles = Request::Post('roles', null);

            $value_error = new ValueException();

            if (strcasecmp($password, $confirm) != 0) {
                $value_error->Prepare('error', 'konfirmasi password tidak sama');
                $value_error->Throws(array(), 'konfirmasi password tidak sama');
            }

            $result = \model\Operator::Create(array(
                'created' => $this->GetServerDateTime(),
                'fullname' => $fullname,
                'username' => $username,
                'password' => md5($password),
                'roles' => $roles,
                'cuid' => 1,
                'muid' => 1
            ));

            if ($result) {
                $this->RedirectTo(BASE_URL . 'operators');
            } else {
                $value_error->Prepare('error', 'simpan data gagal');
                $value_error->Throws(array(), 'simpan data gagal');
            }
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