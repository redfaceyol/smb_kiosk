<?php

namespace App\Controllers\Api2;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\api2\KioskModel;

class Kiosk extends BaseController
{
    private $kiosk_model;
  
    public function initController(
      RequestInterface $request,
      ResponseInterface $response,
      LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
  
        $this->kiosk_model = model(KioskModel::class);
    }

    public function index()
    {
        return view('api/index');
    }
    
    public function shopCheck()
    {
        $data = $this->kiosk_model->shopCheck();

        echo json_encode($data);
    }
    
    public function loadMenu1()
    {
        $data = $this->kiosk_model->loadMenu1();

        echo json_encode($data);
    }
    
    public function loadMenu2()
    {
        $data = $this->kiosk_model->loadMenu2();

        echo json_encode($data);
    }
    
    public function loadMenu3()
    {
        $data = $this->kiosk_model->loadMenu3();

        echo json_encode($data);
    }
}
