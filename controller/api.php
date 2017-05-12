<?php

namespace controller;

use Exception;
use model\Avatars;
use model\Credentials;
use model\Permissions;
use model\Users;
use pukoframework\pda\DBI;
use pukoframework\pte\Service;

/**
 * Class api
 * @package controller
 *
 * #Auth true
 */
class api extends Service
{

    #region internal
    public function permission($appId = null)
    {
        if ($appId == null) {
            throw new Exception('app id required');
        }
        $data['Permission'] = Permissions::GetByApp($appId);
        return $data;
    }
    #end region internal

    /**
     * @param $userId
     *
     * API for retrive user additional data
     */
    public function exchange($userId)
    {

    }

    /**
     * @param null $userId
     *
     * API for fetch user latest avatar
     */
    public function avatar($userId = null)
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
                echo $avatar['filedata'];
            }
        }
    }

    /**
     * @param null $userId
     * @throws Exception
     *
     * API for uploading user new avatar
     */
    public function uploadavatar($userId = null)
    {
        if ($userId == null) {
            throw new Exception('user id required');
        }
        $name = $_FILES['avatar']['name'];
        $type = $_FILES['avatar']['type'];
        $tmp_name = file_get_contents($_FILES['avatar']['tmp_name']);
        $error = $_FILES['avatar']['error'];
        $size = $_FILES['avatar']['size'];

        $crc = sprintf('%x', crc32($tmp_name));
        $hashCode = substr(hash('sha256', date('U') . $name . $crc), 0, 64);

        $prev = Avatars::GetByUserId($userId);
        if ($prev == null) {
            Avatars::Create(array(
                'userid' => $userId,
                'created' => DBI::NOW(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        } else {
            Avatars::Update(array('userid' => $userId), array(
                'modified' => DBI::NOW(),
                'filename' => $name,
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => $type,
                'filedata' => $_FILES['avatar']['tmp_name']
            ));
        }
        $this->RedirectTo(BASE_URL . 'account');
    }

    public function changeavatar($userId = null, $credentialId = null)
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
                'created' => DBI::NOW(),
                'filename' => $credential['type'],
                'hash' => $hashCode,
                'crc' => $crc,
                'extensions' => 'image/png',
                'filedata' => $credential['profilepic']
            ), true);
        } else {
            Avatars::Update(array('userid' => $userId), array(
                'modified' => DBI::NOW(),
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
     * API for get chached image from user credentials
     */
    public function credentialpic($userId = null, $type = null)
    {
        header('Content-Type: image/png');
        if ($userId == null && $type == null) {
            readfile(BASE_URL . 'assets/image/user-icon-placeholder.png');
            die();
        } else {
            $credential = Credentials::GetCredentials($userId, $type);
            if ($credential == null) {
                $this->credentialpic(null, null);
            } else {
                echo $credential['profilepic'];
            }
        }
    }

}