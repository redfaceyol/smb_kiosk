<?php

namespace App\Controllers\Api;
use App\Controllers\BaseController;

class Docs extends BaseController
{
    public function index()
    {
        return view('api/index');
    }
}
