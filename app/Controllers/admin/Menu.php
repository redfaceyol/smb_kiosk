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
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }

  public function kioskList()
  {
    $data["kioskDataList"] = $this->menu_model->getKioskList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/kioskList', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }

  public function menuManage()
  {
    $data["kioskData"] = $this->menu_model->getKioskData();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/menuManage', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }

  public function ajaxGetMenus()
  {
		$result = $this->menu_model->ajaxGetMenus();
		
		echo json_encode($result);
  }

	public function prcCategory()
	{
    if($this->svc_request->getPost('opt')=="u") {
      $this->menu_model->putCategory();
    }
    else {
		  $this->menu_model->postCategory();
    }
	}

  public function ajaxLoadCategory()
  {
		$result = $this->menu_model->ajaxLoadCategory();
		
		echo json_encode($result);
  }

  public function ajaxLoadCategoryList()
  {
		$result = $this->menu_model->ajaxLoadCategoryList();
		
		echo json_encode($result);
  }

	public function delCategory()
	{
    if(md5($this->svc_request->getGet('cid')) == $this->svc_request->getGet('ccid')) {
		  $this->menu_model->delCategory();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/categoryList");
    }
	}

	public function prcMenu()
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

    if($this->svc_request->getPost('opt')=="u") {
      $this->menu_model->putMenu($data);
    }
    else {
		  $this->menu_model->postMenu($data);
    }
	}

  public function ajaxLoadMenu()
  {
		$result = $this->menu_model->ajaxLoadMenu();
		
		echo json_encode($result);
  }

  public function delMenu()
  {
    if(md5($this->svc_request->getGet('mid')) == $this->svc_request->getGet('cmid')) {
		  $this->menu_model->delMenu();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/menuManage?sid=".$this->svc_request->getGet('sid')."&kid=".$this->svc_request->getGet('kid')."&page=");
    }
  }

  public function copyMenu()
  {
    $this->menu_model->copyMenu();
  }

  public function prcReOrderCategory()
  {
    $result = $this->menu_model->prcReOrderCategory();
		
		echo json_encode($result);
  }

  public function prcReOrderMenu()
  {
    $result = $this->menu_model->prcReOrderMenu();
		
		echo json_encode($result);
  }

  public function prcReOrderOptiongroup()
  {
    $result = $this->menu_model->prcReOrderOptiongroup();
		
		echo json_encode($result);
  }

  public function prcReOrderOption()
  {
    $result = $this->menu_model->prcReOrderOption();
		
		echo json_encode($result);
  }

  public function ajaxGetOptions()
  {
		$result = $this->menu_model->ajaxGetOptions();
		
		echo json_encode($result);
  }

	public function prcOptiongroup()
	{
    if($this->svc_request->getPost('opt')=="d") {
      $result = $this->menu_model->delOptiongroup();
    }
    else if($this->svc_request->getPost('opt')=="u") {
      $result = $this->menu_model->putOptiongroup();
    }
    else {
		  $result = $this->menu_model->postOptiongroup();
    }

    echo json_encode($result);
	}

  public function ajaxLoadOptiongroup()
  {
		$result = $this->menu_model->ajaxLoadOptiongroup();
		
		echo json_encode($result);
  }

	public function prcOption()
	{
    if($this->svc_request->getPost('opt')=="d") {
      $result = $this->menu_model->delOption();
    }
    else if($this->svc_request->getPost('opt')=="u") {
      $result = $this->menu_model->putOption();
    }
    else {
		  $result = $this->menu_model->postOption();
    }

    echo json_encode($result);
	}

  public function ajaxLoadOption()
  {
		$result = $this->menu_model->ajaxLoadOption();
		
		echo json_encode($result);
  }

  public function delAllMenu()
  {
    if(md5($this->svc_request->getGet('kid')) == $this->svc_request->getGet('ckid')) {
		  $this->menu_model->delAllMenu();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/kioskList?sid=".$this->svc_request->getGet('sid')."&kid=".$this->svc_request->getGet('kid')."&page=".$this->svc_request->getGet('page'));
    }
  }

  public function copyKioskMenu()
  {
		$this->menu_model->copyKioskMenu();
  }

  public function searchShop()
  {
		$result = $this->menu_model->searchShop();
		
		echo json_encode($result);
  }

  public function copyShopMenu()
  {
		$this->menu_model->copyShopMenu();
  }







  public function categoryList()
  {
    $data = $this->menu_model->getCategoryList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/categoryList', $data).
           view('admin/common/html_footer', $data);
  }

  public function categoryRegiste()
  {
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/menu/categoryRegiste', $data).
           view('admin/common/html_footer', $data);
  }

  public function findShop()
  {
		$result = $this->menu_model->findShop();
		
		echo json_encode($result);
  }

  public function categoryModify()
  {
    if(md5($this->svc_request->getGet('cid')) == $this->svc_request->getGet('ccid')) {
      $data["categoryData"] = $this->menu_model->getCategoryData();
      $data["_request"] = $this->svc_request;

      return view('admin/common/html_header', $data).
            view('admin/common/menu', $data).
            view('admin/menu/categoryModify', $data).
            view('admin/common/html_footer', $data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/categoryList");
    }
  }

	public function putCategory()
	{
    if(md5($this->svc_request->getPost('cid')) == $this->svc_request->getPost('ccid')) {
      $this->menu_model->putCategory();
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/categoryList");
    }
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

      $this->menu_model->putMenu($data);
    }
    else {
      alert('잘못된 호출입니다.', "/admin/menu/menuModify?sid=".$this->svc_request->getPost('sid')."&oid=".$this->svc_request->getPost('oid')."&cid=".md5($this->svc_request->getPost('oid'))."&page=");
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
