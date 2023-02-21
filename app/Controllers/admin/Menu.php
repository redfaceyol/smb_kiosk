<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\MenuModel;

class Menu extends BaseController
{
  private $menu_model;
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
				$this->menu_model = model(MenuModel::class);

        $this->svc_request = service('request');
        $this->svc_response = service('response');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $this->svc_response->redirect('/admin/menu/shopList');
  }

  public function shopList()
  {
    $data = $this->menu_model->getShopList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/shopList', $data).
           view('admin/common/html_footer', $data);
  }

  public function menuList()
  {
    $data = $this->menu_model->getMenuList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/menuList', $data).
           view('admin/common/html_footer', $data);
  }

  public function menuRegiste()
  {
    $data = array();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/menuRegiste', $data).
           view('admin/common/html_footer', $data);
  }

	public function checkID()
	{
		$result = $this->menu_model->checkID();
		
		echo json_encode($result);
	}

	public function postMenu()
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

		$this->menu_model->postMenu($data);
	}

  public function menuModify()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $data["menuData"] = $this->menu_model->getMenuData();
      $data["_request"] = $this->svc_request;

      return view('admin/common/html_header', $data).
            view('admin/common/menu', $data).
            view('admin/menu/menuModify', $data).
            view('admin/common/html_footer', $data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu");
    }
  }

  public function putMenu()
  {
    if(md5($this->svc_request->getPost('oid')) == $this->svc_request->getPost('cid')) {
      $this->menu_model->putMenu();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/menuModify?oid=".$this->svc_request->getPost('oid')."&cid=".md5($this->svc_request->getPost('oid'))."&page=");
    }
  }

  public function delMenu()
  {
    if(md5($this->svc_request->getGet('oid')) == $this->svc_request->getGet('cid')) {
      $this->menu_model->delMenu();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/menuList?page=".$this->svc_request->getGet('page'));
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
