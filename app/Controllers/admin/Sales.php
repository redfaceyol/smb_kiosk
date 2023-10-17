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

  public function salesDashboard()
  {
    $tmpdata = $this->sales_model->getSalesDashboard();

    $datenow = date_create(date("Y-m-d"));
    
    for($i=0; $i<7; $i++) {
      $rsltDataDate[$i] = date_format($datenow,"d");

      foreach($tmpdata["dailysalelist"] as $tmpitem) {
        if($tmpitem->payment_day == $rsltDataDate[$i]) {
          $rsltDataVal[$i] = $tmpitem->amount;
        }
      }

      if(!isset($rsltDataVal[$i])) {
        $rsltDataVal[$i] = 0;
      }
      
      date_sub($datenow, date_interval_create_from_date_string("1 days"));
    }

    $data["startYear"] = $this->sales_model->getStartYear();
    $data["dailydays"] = $rsltDataDate;
    $data["dailyvals"] = $rsltDataVal;

    $getYear = ($this->svc_request->getGet('year')?$this->svc_request->getGet('year'):date("Y"));
    $getMonth = ($this->svc_request->getGet('month')?$this->svc_request->getGet('month'):date("n"));
    $getDay = ($this->svc_request->getGet('day')?$this->svc_request->getGet('day'):date("j"));

    $data["yearSales"] = $this->sales_model->getYearSales($getYear);
    $data["monthSales"] = $this->sales_model->getMonthSales($getYear, $getMonth);
    $data["daySales"] = $this->sales_model->getDaySales($getYear, $getMonth, $getDay);

    $data["_request"] = $this->svc_request;

    return view('admin/common/html_header', $data).
           view('admin/common/menu', $data).
           view('admin/sales/salesDashboard', $data).
           view('admin/common/help', $data).
           view('admin/common/html_footer', $data);
  }
}
