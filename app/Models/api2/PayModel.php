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

  public function setBaseTabe()
  {
		$query = $this->db->query("create table if not exists payment_".date("Y")." like payment_base");
		$query = $this->db->query("create table if not exists order_".date("Y")." like order_base");
		$query = $this->db->query("create table if not exists orderdetail_".date("Y")." like orderdetail_base");
		$query = $this->db->query("create table if not exists pointhistory_".date("Y")." like pointhistory_base");
		$query = $this->db->query("create table if not exists kioskstatus_".date("Y")." like kioskstatus_base");
  }

  public function savePay()
  {
    $this->setBaseTabe();

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
                            
              $builder = $this->db->table("order_".date("Y"));

              $data = [
                'shop' => $this->request->getPost('sid'), 
                'kiosk' => $this->request->getPost('kioskid'), 
                'order_date' => $this->request->getPost('orderdate'), 
                'order_datetime' => $this->request->getPost('orderdate'), 
                'order_number' => $this->request->getPost('ordernumber'), 
                'order_count' => $this->request->getPost('ordercount'), 
                'total_amount' => $this->request->getPost('totalamount'), 
              ];
              
              $builder->set('registe_datetime', "now()", false);
              $builder->set($data);
              $builder->insert();

              $order_id = $this->db->insertID();
              
              $builder = $this->db->table("orderdetail_".date("Y"));

              $menulist = json_decode($this->request->getPost('orderdata'), true);

              foreach ($menulist["menuList"] as $menuitem) {
                $data = [
                  'shop' => $this->request->getPost('sid'), 
                  'kiosk' => $this->request->getPost('kioskid'), 
                  'order_id' => $order_id, 
                  'order_table' => '', 
                  'order_date' => $this->request->getPost('orderdate'), 
                  'order_datetime' => $this->request->getPost('orderdate'), 
                  'menu' => $menuitem["id"], 
                  'menu_title' => $menuitem["title"], 
                  'menu_price' => $menuitem["price"], 
                  'menu_quantity' => $menuitem["quantity"], 
                  'menu_option' => json_encode($menuitem["optionList"]), 
                ];
                
                $builder->set('registe_datetime', "now()", false);
                $builder->set($data);
                $builder->insert();
              }

              $builder = $this->db->table("payment_".date("Y"));

              $van_data = array();
              if($this->request->getPost('van') == "KSNET") {
                $paystring = iconv("UTF-8", "CP949", $this->request->getPost('paystring'));
                $van_data = [
                  'KSNET_data1'  => trim(iconv("CP949", "UTF-8", substr($paystring,   5,   2))), 
                  'KSNET_data2'  => trim(iconv("CP949", "UTF-8", substr($paystring,   7,   2))), 
                  'KSNET_data3'  => trim(iconv("CP949", "UTF-8", substr($paystring,   9,   4))), 
                  'KSNET_data4'  => trim(iconv("CP949", "UTF-8", substr($paystring,  13,   1))), 
                  'KSNET_data5'  => trim(iconv("CP949", "UTF-8", substr($paystring,  14,  10))), 
                  'KSNET_data6'  => trim(iconv("CP949", "UTF-8", substr($paystring,  24,   4))), 
                  'KSNET_data7'  => trim(iconv("CP949", "UTF-8", substr($paystring,  28,  12))), 
                  'KSNET_data8'  => trim(iconv("CP949", "UTF-8", substr($paystring,  40,   1))), 
                  'KSNET_data9'  => trim(iconv("CP949", "UTF-8", substr($paystring,  41,   4))), 
                  'KSNET_data10' => trim(iconv("CP949", "UTF-8", substr($paystring,  45,   4))), 
                  'KSNET_data11' => trim(iconv("CP949", "UTF-8", substr($paystring,  49,  12))), 
                  'KSNET_data12' => trim(iconv("CP949", "UTF-8", substr($paystring,  61,   1))), 
                  'KSNET_data13' => trim(iconv("CP949", "UTF-8", substr($paystring,  62,  16))), 
                  'KSNET_data14' => trim(iconv("CP949", "UTF-8", substr($paystring,  78,  16))), 
                  'KSNET_data15' => trim(iconv("CP949", "UTF-8", substr($paystring,  94,  12))), 
                  'KSNET_data16' => trim(iconv("CP949", "UTF-8", substr($paystring, 106,  20))), 
                  'KSNET_data17' => trim(iconv("CP949", "UTF-8", substr($paystring, 126,  15))), 
                  'KSNET_data18' => trim(iconv("CP949", "UTF-8", substr($paystring, 141,   2))), 
                  'KSNET_data19' => trim(iconv("CP949", "UTF-8", substr($paystring, 143,  16))), 
                  'KSNET_data20' => trim(iconv("CP949", "UTF-8", substr($paystring, 159,   2))), 
                  'KSNET_data21' => trim(iconv("CP949", "UTF-8", substr($paystring, 161,  16))), 
                  'KSNET_data22' => trim(iconv("CP949", "UTF-8", substr($paystring, 177,   2))), 
                  'KSNET_data23' => trim(iconv("CP949", "UTF-8", substr($paystring, 179,  16))), 
                  'KSNET_data24' => trim(iconv("CP949", "UTF-8", substr($paystring, 195,   9))), 
                  'KSNET_data25' => trim(iconv("CP949", "UTF-8", substr($paystring, 204,   9))), 
                  'KSNET_data26' => trim(iconv("CP949", "UTF-8", substr($paystring, 213,   9))), 
                  'KSNET_data27' => trim(iconv("CP949", "UTF-8", substr($paystring, 222,   9))), 
                  'KSNET_data28' => trim(iconv("CP949", "UTF-8", substr($paystring, 231,  20))), 
                  'KSNET_data29' => trim(iconv("CP949", "UTF-8", substr($paystring, 251,  40))), 
                  'KSNET_data30' => trim(iconv("CP949", "UTF-8", substr($paystring, 291,   5))), 
                  'KSNET_data31' => trim(iconv("CP949", "UTF-8", substr($paystring, 296,  40))), 
                  'KSNET_data32' => trim(iconv("CP949", "UTF-8", substr($paystring, 336,  30))), 
                  'KSNET_data33' => trim(iconv("CP949", "UTF-8", substr($paystring, 366,  90))), 
                ];

                $payment_datetime = trim(iconv("CP949", "UTF-8", substr($paystring,  49,  12)));
                $card_name = trim(iconv("CP949", "UTF-8", substr($paystring, 143,  16)));
                $card_number = trim(iconv("CP949", "UTF-8", substr($paystring, 336,  30)));
                $authnumber = trim(iconv("CP949", "UTF-8", substr($paystring,  94,  12)));
                $rsltStatus = trim(iconv("CP949", "UTF-8", substr($paystring,  40,  1)));
              }

              $data = [
                'shop' => $this->request->getPost('sid'), 
                'kiosk' => $this->request->getPost('kioskid'), 
                'order_id' => $order_id, 
                'method' => $this->request->getPost('method'), 
                'payment_datetime' => $payment_datetime, 
                'amount' => $this->request->getPost('totalamount'), 
                'card_name' => $card_name, 
                'card_number' => $card_number, 
                'authnumber' => $authnumber, 
                'installment' => $this->request->getPost('installment'), 
                'van' => $this->request->getPost('van'), 
                'paystring' => $this->request->getPost('paystring'), 
              ];
              
              $builder->set('registe_datetime', "now()", false);
              $builder->set(array_merge($data, $van_data));
              $builder->insert();

              if($this->request->getPost('pointtelnum') && $this->request->getPost('point') > 0 && $rsltStatus=="O") {
                $point_sql = "select id, totalpoint from point where shop='".$this->request->getPost('sid')."' and telnumber='".$this->request->getPost('pointtelnum')."'";
                $point_query = $this->db->query($point_sql);
                $point_rows = $point_query->getNumRows();

                if($point_rows) {
                  $point_result = $point_query->getResult();
                  $totalpoint = $point_result['0']->totalpoint + $this->request->getPost('point');

                  $this->db->query("update point set totalpoint='".$totalpoint."', lastpointyear='".date("Y")."' where id='".$point_result['0']->id."'");
                }
                else {
                  $totalpoint = $this->request->getPost('point');

                  $builder = $this->db->table("point");                
                  $data = [
                    'shop' => $this->request->getPost('sid'), 
                    'telnumber' => $this->request->getPost('pointtelnum'), 
                    'totalpoint' => $totalpoint, 
                    'startpointyear' => date("Y"), 
                    'lastpointyear' => date("Y"), 
                  ];
                  $builder->set($data);
                  $builder->insert();
                }

                $builder = $this->db->table("pointhistory_".date("Y"));
                
                $data = [
                  'shop' => $this->request->getPost('sid'), 
                  'telnumber' => $this->request->getPost('pointtelnum'), 
                  'point' => $this->request->getPost('point'), 
                  'totalpoint' => $totalpoint, 
                  'pointdate' => $payment_datetime, 
                ];
                
                $builder->set('registe_datetime', "now()", false);
                $builder->set($data);
                $builder->insert();
              }
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