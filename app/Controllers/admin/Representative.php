<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\RepresentativeModel;

class Representative extends BaseController
{
  private $representative_model;
  private $svc_request;
  private $svc_response;
  private $session;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      $this->session = session();
      
			if($this->session->member_id) {
				$this->representative_model = model(RepresentativeModel::class);

        $this->svc_request = service('request');
        $this->svc_response = service('response');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $this->svc_response->redirect('/admin/representative/representativeList');
  }

  public function representativeList()
  {
    $data = $this->representative_model->getRepresentativeList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/representative/representativeList', $data).
           view('admin/common/html_footer', $data);
  }

  public function representativeRegiste()
  {
    $data = array();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/representative/representativeRegiste', $data).
           view('admin/common/html_footer', $data);
  }

	public function checkID()
	{
		$result = $this->representative_model->checkID();
		
		echo json_encode($result);
	}

	public function postRepresentative()
	{
		$this->representative_model->postRepresentative();
	}

  public function representativeModify()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $data["representativeData"] = $this->representative_model->getRepresentativeData();
      $data["_request"] = $this->svc_request;

      return view('admin/common/html_header', $data).
            view('admin/common/menu', $data).
            view('admin/representative/representativeModify', $data).
            view('admin/common/html_footer', $data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/representative");
    }
  }

  public function putRepresentative()
  {
    if(md5($this->svc_request->getPost('oid')) == $this->svc_request->getPost('cid')) {
      $this->representative_model->putRepresentative();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/representative/representativeModify?oid=".$this->svc_request->getPost('oid')."&cid=".md5($this->svc_request->getPost('oid'))."&page=");
    }
  }

  public function delRepresentative()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $this->representative_model->delRepresentative();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/representative/representativeList?page=".$this->svc_request->getGet('page'));
    }
  }





  public function putMyinfo()
  {
    
    if($this->session->member_id == $this->svc_request->getPost('oid')) {
      $this->membermodel->putMyinfo();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/myaccount");
    }
  }
}
