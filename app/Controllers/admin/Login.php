<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Login extends BaseController
{
  private $skin_path = "/assets/skin/login1/";

  public function index()
  {
    $data["skin_path"] = $this->skin_path;

    return view('admin/common/html_header', $data).
           view('admin/login1/login').
           view('admin/common/html_footer', $data);
  }
}
