<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\KioskModel;

class Kiosk extends BaseController
{
  private $kiosk_model;
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
				$this->kiosk_model = model(KioskModel::class);

        $this->svc_request = service('request');
        $this->svc_response = service('response');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $this->svc_response->redirect('/admin/kiosk/kioskList');
  }

  public function kioskList()
  {
    $data = $this->kiosk_model->getKioskList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/kiosk/kioskList', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }

  public function kioskRegiste()
  {
    $data = array();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/kiosk/kioskRegiste', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }

	public function checkID()
	{
		$result = $this->kiosk_model->checkID();
		
		echo json_encode($result);
	}

  public function findShop()
  {
		$result = $this->kiosk_model->findShop();
		
		echo json_encode($result);
  }

  public function selectShop()
  {
		$result = $this->kiosk_model->selectShop();
		
		echo json_encode($result);
  }

	public function postKiosk()
	{
		$this->kiosk_model->postKiosk();
	}

  public function kioskModify()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $data["kioskData"] = $this->kiosk_model->getKioskData();
      $data["_request"] = $this->svc_request;

      return view('admin/common/html_header', $data).
            view('admin/common/menu', $data).
            view('admin/kiosk/kioskModify', $data).
            view('admin/common/help', $data).
            view('admin/common/html_footer', $data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/kiosk");
    }
  }

  public function putKiosk()
  {
    if(md5($this->svc_request->getPost('oid')) == $this->svc_request->getPost('cid')) {
      $this->kiosk_model->putKiosk();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/kiosk/kioskModify?oid=".$this->svc_request->getPost('oid')."&cid=".md5($this->svc_request->getPost('oid'))."&page=");
    }
  }

  public function delKiosk()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $this->kiosk_model->delKiosk();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/kiosk/kioskList?page=".$this->svc_request->getGet('page'));
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
