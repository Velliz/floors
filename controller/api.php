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
     * @throws Exception
     *
     * #Auth true
     */
    public function upload_avatar($userId = null)
    {
        if ($userId == null) {
            throw new Exception('user id required');
        }
        $name = $_FILES['avatar']['name'];
        $type = $_FILES['avatar']['type'];
        $tmp_name = file_get_contents($_FILES['avatar']['tmp_name']);

        //$error = $_FILES['avatar']['error'];
        //$size = $_FILES['avatar']['size'];

        $crc = sprintf('%x', crc32($tmp_name));
        $hashCode = substr(hash('sha256', date('U') . $name . $crc), 0, 64);

        $prev = Avatars::GetByUserId($userId);
        if ($prev == null) {
            Avatars::Create(array(
                'userid' => $userId,
                'created' => $this->GetServerDateTime(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        } else {
            Avatars::Update(array('userid' => $userId), array(
                'modified' => $this->GetServerDateTime(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        }
        $this->RedirectTo(BASE_URL . 'account');
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

    public function OnInitialize()
    {

    }
}