<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class PayModel extends Model
{
  protected $table = 'payment';
	
	private $request;

	protected function initialize()
	{
		$this->request = service('request');
	}

  public function savePay()
  {
    $resultVal = array();

    try {
      if($this->request->getPost('sid')) {
        if($this->request->getPost('kioskid')) {
          $shop_sql = "select id from shop where id='".$this->request->getPost('sid')."'";
          $shop_query = $this->db->query($shop_sql);
          $shop_rows = $shop_query->getNumRows();

          if($shop_rows) {
            $kiosk_sql = "select id from kiosk where id='".$this->request->getPost('kioskid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
  
            if($kiosk_rows) {
              $resultVal['code'] = "100";

              $sql = "insert into payment (shop, kiosk, van, paystring) values('".$this->request->getPost('sid')."', '".$this->request->getPost('kioskid')."', '".$this->request->getPost('van')."', '".$this->request->getPost('paystring')."')";
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 KIOSK아이디";
            }
          }
          else {
            $resultVal['code'] = "510";
            $resultVal['msg'] = "등록되지 않은 매장아이디";
          }
        }
        else {
          $resultVal['code'] = "501";
          $resultVal['msg'] = "입력된 KIOSK아이디 없음";
        }
      }
      else {
        $resultVal['code'] = "500";
        $resultVal['msg'] = "입력된 매장아이디 없음";
      }
    }
    catch(Exception $e) {
      $resultVal['code'] = "599";
      $resultVal['msg'] = $e->getMessage();
    } 

    return $resultVal;
  }
}
?>