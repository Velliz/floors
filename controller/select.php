<?php

namespace controller;

use model\Applications;
use pukoframework\pte\View;

/**
 * Class select
 * @package controller
 */
class select extends View
{

    public function select()
    {
        $data['app'] = Applications::GetAll();
        return $data;
    }

    public function OnInitialize()
    {
    }
}