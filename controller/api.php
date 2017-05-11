<?php

namespace controller;

use Exception;
use model\Avatars;
use model\Permissions;
use model\Users;
use pukoframework\pte\Service;

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

    public function exchange($userId)
    {

    }

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

}