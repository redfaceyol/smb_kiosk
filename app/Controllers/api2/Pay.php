<?php

namespace App\Controllers\Api2;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\api2\PayModel;

class Pay extends BaseController
{
    private $pay_model;
  
    public function initController(
      RequestInterface $request,
      ResponseInterface $response,
      LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
  
        $this->pay_model = model(PayModel::class);
    }
    
    public function savePay()
    {
        $data = $this->pay_model->savePay();

        echo json_encode($data);
    }
}
