<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\admin\MenuModel;

class Menu extends BaseController
{
  private $menu_model;
  private $svc_uri;

  public function initController(
    RequestInterface $request,
    ResponseInterface $response,
    LoggerInterface $logger
  ) {
      parent::initController($request, $response, $logger);

      $this->menu_model = model(MenuModel::class);

      $this->svc_uri = service('uri');
  }

  public function index()
  {
    
  }

  public function image()
  {
		header("Content-type: image/jpg");
		echo $this->menu_model->getImage($this->svc_uri->getSegment(3));
  }
}
