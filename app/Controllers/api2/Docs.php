<?php

namespace App\Controllers\Api2;

use App\Controllers\BaseController;

class Docs extends BaseController
{
    public function index()
    {
        $resultVal = array();

        $resultVal["status"] = "OK";

        echo json_encode($resultVal);
    }
}
