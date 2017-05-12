<?php
namespace controller\manage;

use Exception;
use model\Operator;
use model\Users;
use pukoframework\auth\Auth;
use pukoframework\auth\Session;
use pukoframework\pda\DBI;
use pukoframework\pte\View;
use pukoframework\Request;

/**
 * Class permissions
 * @package controller\manage
 *
 * #Auth true
 * #Master master.html
 * #Value menu_applications active
 */
class permissions extends View implements Auth
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
     * @param $appId
     * #Value title New Permissions
     */
    public function create($appId)
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $pname = Request::Post('pname', null);
            $pcode = Request::Post('pcode', null);
            $desc = Request::Post('description', null);

            $broker = array(
                'cuid' => $data['id'],
                'appid' => $appId,
                'created' => DBI::NOW(),
                'pname' => $pname,
                'pcode' => $pcode,
                'description' => $desc,
            );

            \model\Permissions::Create($broker);

            $this->RedirectTo(BASE_URL . 'application/detail/' . $appId);
        }
    }

    public function edit($permissionId)
    {
        $data = Session::Get($this)->GetLoginData();

        if (Request::IsPost()) {
            $pname = Request::Post('pname', null);
            $pcode = Request::Post('pcode', null);
            $desc = Request::Post('description', null);

            $broker = array(
                'description' => $desc,
                'muid' => $data['id'],
                'modified' => DBI::NOW(),
                'pname' => $pname,
                'pcode' => $pcode,
            );

            \model\Permissions::Update(array('id' => $permissionId), $broker);
        }
        $data['Permissions'] = \model\Permissions::GetID($permissionId);
        return $data;
    }

    public function delete($permissionId)
    {
        \model\Permissions::Update(array('id' => $permissionId),
            array(
                'dflag' => 1,
            )
        );
        $this->RedirectTo(BASE_URL . 'applications');
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
            return Operator::GetID($userAccount[1]);
        } else {
            return Users::GetID($userAccount[1])[0];
        }
    }
}