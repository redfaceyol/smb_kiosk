<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\ImageModel;

class Image extends BaseController
{
  private $image_model;
  private $svc_response;
  private $svc_uri;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      $this->image_model = model(ImageModel::class);

      $this->svc_response = service('response');
      $this->svc_uri = service('uri');
  }

  public function index()
  {
    
  }

  public function menu_image()
  {
		$data =  $this->image_model->getMenuImage($this->svc_uri->getSegment(3));
    $this->svc_response->setHeader("Content-Type", "image/jpg");
    $this->svc_response->setHeader("Content-Length", strlen($data));
    $this->svc_response->setStatusCode(200)->setBody($data);
    $this->svc_response->send();
  }

  public function menu_thumbimage()
  {
		$data = $this->image_model->getMenuThumbImage($this->svc_uri->getSegment(3));
    $this->svc_response->setHeader("Content-Type", "image/jpg");
    $this->svc_response->setHeader("Content-Length", strlen($data));
    $this->svc_response->setStatusCode(200)->setBody($data);
    $this->svc_response->send();
  }

  public function shop_image()
  {
		$data = $this->image_model->getShopImage($this->svc_uri->getSegment(3));
    $this->svc_response->setHeader("Content-Type", "image/jpg");
    $this->svc_response->setHeader("Content-Length", strlen($data));
    $this->svc_response->setStatusCode(200)->setBody($data);
    $this->svc_response->send();
  }
}
