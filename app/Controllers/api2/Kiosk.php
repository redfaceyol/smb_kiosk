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
    
    public function loadCategory()
    {
        $data = $this->kiosk_model->loadCategory();

        echo json_encode($data);
    }
    
    public function loadMenu()
    {
        $data = $this->kiosk_model->loadMenu();

        echo json_encode($data);
    }
    
    public function loadOptiongroup()
    {
        $data = $this->kiosk_model->loadOptiongroup();

        echo json_encode($data);
    }
    
    public function loadOption()
    {
        $data = $this->kiosk_model->loadOption();

        echo json_encode($data);
    }
    
    public function saveSoldout()
    {
        $data = $this->kiosk_model->saveSoldout();

        echo json_encode($data);
    }
    
    public function saveKioskStatus()
    {
        $data = $this->kiosk_model->saveKioskStatus();

        echo json_encode($data);
    }



    
    /*
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
    */
}
