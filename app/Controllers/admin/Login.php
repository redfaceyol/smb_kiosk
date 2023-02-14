<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\MemberModel;

class Login extends BaseController
{
  private $login_skin_name = "login1";

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      //$membermodel = model(MemberModel::class);
  }

  public function index()
  {
    $data["login_skin_name"] = $this->login_skin_name;

    return view('admin/common/html_header', $data).
           view('admin/login1/login').
           view('admin/common/html_footer', $data);
  }

  public function prcLogin()
  {
    $membermodel = model(MemberModel::class);
    $membermodel->prcLogin();
  }
}
