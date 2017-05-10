<?php

namespace controller;

use Exception;
use model\Permissions;
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
        if ($userId == null) {

        }
    }

}