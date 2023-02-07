<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Login extends BaseController
{
    public function index()
    {
        return view('admin/common/html_header').
               view('admin/login1/login').
               view('admin/common/html_footer');
    }
}
