<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\ShopModel;

class Shop extends BaseController
{
  private $shop_model;
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
				$this->shop_model = model(ShopModel::class);

        $this->svc_request = service('request');
        $this->svc_response = service('response');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $this->svc_response->redirect('/admin/shop/shopList');
  }

  public function shopList()
  {
    $data = $this->shop_model->getShopList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/shop/shopList', $data).
           view('admin/common/html_footer', $data);
  }

  public function shopRegiste()
  {
    $data = array();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/shop/shopRegiste', $data).
           view('admin/common/html_footer', $data);
  }

	public function checkID()
	{
		$result = $this->shop_model->checkID();
		
		echo json_encode($result);
	}

  public function findRepresentative()
  {
		$result = $this->shop_model->findRepresentative();
		
		echo json_encode($result);
  }

  public function selectRepresentative()
  {
		$result = $this->shop_model->selectRepresentative();
		
		echo json_encode($result);
  }

	public function postShop()
	{
    $validationRule = [
      'imagefile' => [
          'label' => 'Image File',
          'rules' => 'uploaded[imagefile]'
              . '|is_image[imagefile]'
              . '|mime_in[imagefile,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
              . '|max_size[imagefile,0]'
              . '|max_dims[imagefile,0,0]',
      ],
    ];

    $img = $this->request->getFile('imagefile');

		$data = array();

		if (! $img->hasMoved()) {
			$data["imagefile"] = array('upload_data' => $img);
		}
		else {
			$data["imagefile"] = array('error' => $img->error);
			$data["imagefile"]["upload_data"]["file_name"] = "";
		}

		$this->shop_model->postShop($data);
	}

  public function shopModify()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $data["shopData"] = $this->shop_model->getShopData();
      $data["_request"] = $this->svc_request;

      return view('admin/common/html_header', $data).
            view('admin/common/menu', $data).
            view('admin/shop/shopModify', $data).
            view('admin/common/html_footer', $data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/shop");
    }
  }

  public function putShop()
  {
    if(md5($this->svc_request->getPost('oid')) == $this->svc_request->getPost('cid')) {
      $validationRule = [
        'imagefile' => [
            'label' => 'Image File',
            'rules' => 'uploaded[imagefile]'
                . '|is_image[imagefile]'
                . '|mime_in[imagefile,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                . '|max_size[imagefile,0]'
                . '|max_dims[imagefile,0,0]',
        ],
      ];
  
      $img = $this->request->getFile('imagefile');
  
      $data = array();
  
      if (! $img->hasMoved()) {
        $data["imagefile"] = array('upload_data' => $img);
      }
      else {
        $data["imagefile"] = array('error' => $img->error);
        $data["imagefile"]["upload_data"]["file_name"] = "";
      }
  
      $this->shop_model->putShop($data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/shop/shopModify?oid=".$this->svc_request->getPost('oid')."&cid=".md5($this->svc_request->getPost('oid'))."&page=");
    }
  }

  public function delShop()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $this->shop_model->delShop();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/shop/shopList?page=".$this->svc_request->getGet('page'));
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
