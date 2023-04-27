<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\SalesModel;

class Sales extends BaseController
{
  private $sales_model;
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
				$this->sales_model = model(SalesModel::class);

        $this->svc_request = service('request');
        $this->svc_response = service('response');
			}
			else {
				alert('로그인해주세요.', "/admin/login");
			}
  }

  public function index()
  {
    $this->svc_response->redirect('/admin/sales/ShopList');
  }

  public function ShopList()
  {
    $data = $this->sales_model->getShopList();
    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/sales/shopList', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }
}
