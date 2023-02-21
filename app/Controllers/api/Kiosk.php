<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\api\KioskModel;

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
    
    public function kiosk_login()
    {
        $data = $this->kiosk_model->kiosk_login();

        echo json_encode($data);
    }
    
    public function menulist()
    {
        $data = $this->kiosk_model->menulist();

        echo json_encode($data);
    }
}
