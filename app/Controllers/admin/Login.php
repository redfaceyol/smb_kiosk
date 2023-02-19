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

  private $membermodel;
  private $session;
  private $svc_response;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

			$this->membermodel = model(MemberModel::class);

      $this->session = session();
      $this->svc_response = service('response');
  }

  public function index()
  {
    $this->session->destroy();

    $data["login_skin_name"] = $this->login_skin_name;

    return view('admin/login1/html_header', $data).
           view('admin/login1/login').
           view('admin/login1/html_footer', $data);
  }

  public function prcLogin()
  {
    $this->membermodel->prcLogin();
  }

  public function Logout()
  {
    $this->session->destroy();

    $this->svc_response->redirect("/admin/login");
  }
}
