<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\MemberModel;

class Dashboard extends BaseController
{
  private $member_model;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      $session = session();
      
			if($session->member_id) {
				$this->member_model = model(MemberModel::class);
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $data = array();

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/dashboard', $data).
           view('admin/common/html_footer', $data);
  }
}
