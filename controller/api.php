<?php

namespace controller;

use Exception;
use model\Applications;
use model\Authorization;
use model\Avatars;
use model\Credentials;
use model\Logs;
use model\Permissions;
use model\Users;
use pukoframework\pte\Service;
use pukoframework\Request;

/**
 * Class api
 * @package controller
 */
class api extends Service
{

    public function OnInitialize()
    {

    }

    #region internal

    /**
     * @return mixed
     * @throws Exception
     *
     * #Auth true
     */
    public function permission()
    {
        $appId = Request::Post('appid', null);
        if ($appId == null) {
            throw new Exception('app id required');
        }
        $data['Permission'] = Permissions::GetByApp($appId);
        return $data;
    }

    /**
     * @param null $userId
     * @return mixed
     * @throws Exception
     *
     * #Auth true
     */
    public function authorization($userId = null)
    {
        $appId = Request::Post('appid', null);
        if ($appId == null) {
            throw new Exception('app id required');
        }
        $data['Permission'] = Authorization::GetUserToAppAuthorization($userId, $appId);
        return $data;
    }

    /**
     * @param null $userId
     * @return array
     * @throws Exception
     *
     * #Auth true
     */
    public function upload_avatar($userId = null)
    {
        if ($userId == null) {
            throw new Exception('user id required');
        }

        $data = array();

        $name = $_FILES['avatar']['name'];
        $type = $_FILES['avatar']['type'];
        $tmp_name = file_get_contents($_FILES['avatar']['tmp_name']);

        $data['nama'] = $name;
        $data['type'] = $type;

        //$error = $_FILES['avatar']['error'];
        //$size = $_FILES['avatar']['size'];

        $crc = sprintf('%x', crc32($tmp_name));
        $hashCode = substr(hash('sha256', date('U') . $name . $crc), 0, 64);

        $prev = Avatars::GetByUserId($userId);
        if ($prev === null) {
            $data['file'] = Avatars::Create(array(
                'userid' => $userId,
                'created' => $this->GetServerDateTime(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        } else {
            $data['file'] = Avatars::Update(array('userid' => $userId), array(
                'modified' => $this->GetServerDateTime(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        }

        return $data;
    }

    /**
     * @param null $userId
     * @param null $credentialId
     * @throws Exception
     *
     * #Auth true
     */
    public function change_avatar($userId = null, $credentialId = null)
    {
        $credential = Credentials::GetID($credentialId);
        if ($credentialId == null) {
            throw new Exception('preveious credential not found');
        }

        $prev = Avatars::GetByUserId($userId);

        $crc = sprintf('%x', crc32($credential['profilepic']));
        $hashCode = substr(hash('sha256', date('U') . $credential['type'] . $crc), 0, 64);

        if ($prev == null) {
            Avatars::Create(array(
                'userid' => $userId,
                'created' => $this->GetServerDateTime(),
                'filename' => $credential['type'],
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => 'image/png',
                'filedata' => $credential['profilepic']
            ), true);
        } else {
            Avatars::Update(array('userid' => $userId), array(
                'modified' => $this->GetServerDateTime(),
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => 'image/png',
                'filedata' => $credential['profilepic']
            ), true);
        }

        $this->RedirectTo(BASE_URL . 'account');
    }

    /**
     * @param null $userId
     * @param null $type
     *
     * #Auth true
     * API for get cached image from user credentials
     */
    public function credential_picture($userId = null, $type = null)
    {
        header('Content-Type: image/png');
        if ($userId == null && $type == null) {
            readfile(BASE_URL . 'assets/image/user-icon-placeholder.png');
            die();
        } else {
            $credential = Credentials::GetCredentials($userId, $type);
            if ($credential == null) {
                $this->credential_picture(null, null);
            } else {
                echo $credential['profilepic'];
            }
        }
    }
    #end region internal

    /**
     * API for retrive user additional data
     * http://localhost/floors/api/user [POST]
     */
    public function user()
    {
        $token = Request::Post('token', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }
        $userid = Logs::ExchangeTokenWithUserID($token);
        $data['user'] = Users::GetUserData($userid);
        return $data;
    }

    /**
     * API for edit user data
     * http://localhost/floors/api/user/edit [POST]
     */
    public function user_edit()
    {
        $token = Request::Post('token', null);
        $nama = Request::Post('nama', null);
        $email = Request::Post('email', null);
        $telp = Request::Post('telp', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }
        if ($nama == null) {
            throw new Exception("nama not defined");
        }
        if ($email == null) {
            throw new Exception("email not defined");
        }
        if ($telp == null) {
            throw new Exception("telp not defined");
        }
        $userid = Logs::ExchangeTokenWithUserID($token);
        Users::Update(array('id' => $userid), array(
            'muid' => $userid,
            'modified' => $this->GetServerDateTime(),
            'fullname' => $nama,
            'firstemail' => $email,
            'phonenumber' => $telp,
        ));
        $data['user'] = Users::GetUserData($userid);
        return $data;
    }

    /**
     * @param int $userId
     * @param int $width
     *
     * API for fetch user latest avatar
     * http://localhost/floors/api/avatar/1/400 [GET]
     */
    public function avatar($userId = null, $width = 100)
    {
        header('Content-Type: image/png');
        if ($userId == null) {
            readfile(BASE_URL . 'assets/image/user-icon-placeholder.png');
            die();
        } else {
            $avatar = Avatars::GetByUserId($userId);
            if ($avatar == null) {
                $this->avatar(null);
            } else {
                $avatar = imagecreatefromstring($avatar['filedata']);
                echo imagepng(imagescale($avatar, $width));
            }
        }
    }

    /**
     * @return mixed
     * @throws Exception
     *
     * API for fetch user authorization access
     * http://localhost/floors/api/authorized [POST]
     */
    public function authorized()
    {
        $token = Request::Post('token', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }

        $sso = Request::Post('sso', null);
        if ($sso == null) {
            throw new Exception("sso not defined");
        }

        $app = Applications::GetByToken($sso);
        $userid = Logs::ExchangeTokenWithUserID($token);

        $data['auth'] = Authorization::GetUserToAppAuthorization($userid, $app['id']);
        return $data;
    }

    /**
     * @return mixed
     * @throws Exception
     *
     * API for confirm user password
     * ONLY WORKS WITH FLOORS ACCOUNT
     * http://localhost/floors/api/confirm/password [POST]
     */
    public function confirm_password()
    {
        $token = Request::Post('token', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }

        $user_id = Logs::ExchangeTokenWithUserID($token);

        $password = Request::Post('password', null);
        $confirm_password = Request::Post('confirm', null);

        if (strcmp($password, $confirm_password) != 0) {
            throw new Exception('Password confirmation not match');
        }

        $result = Users::IsPasswordTrue($user_id, $password);
        return $result;
    }

    /**
     * @return mixed|null
     * @throws Exception
     *
     * http://localhost/floors/api/login/info [POST]
     */
    public function login_info()
    {
        $token = Request::Post('token', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }

        $login['login'] = Logs::ExchangeTokenWithLoginData($token);
        return $login;
    }

    /**
     * @return mixed
     * @throws Exception
     *
     * http://localhost/floors/api/credential/info [POST]
     */
    public function credential_info()
    {
        $token = Request::Post('token', null);
        if ($token == null) {
            throw new Exception("token not defined");
        }

        $credentials['credentials'] = Logs::ExchangeTokenWithCredentialData($token);
        return $credentials;
    }

    /**
     * @param $app_token
     * @param $auth_code
     * @return mixed
     *
     * http://localhost/floors/api/list/users/b68e52a7222ced010a597241f4be5e06/ADMIN [GET]
     */
    public function list_users($app_token, $auth_code)
    {
        $user['Users'] = Applications::GetUserInApps($app_token, $auth_code);
        return $user;
    }
}