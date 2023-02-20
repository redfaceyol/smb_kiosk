<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\MemberModel;

class MyAccount extends BaseController
{
  private $member_model;
  private $svc_request;
  private $session;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      $this->session = session();
      
			if($this->session->member_id) {
				$this->member_model = model(MemberModel::class);

        $this->svc_request = service('request');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $data["myInfo"] = $this->member_model->getMyInfo();

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/myaccount/myaccount', $data).
           view('admin/common/html_footer', $data);
  }

  public function putMyinfo()
  {
    
    if($this->session->member_id == $this->svc_request->getPost('oid')) {
      $this->member_model->putMyinfo();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/myaccount");
    }
  }
}
