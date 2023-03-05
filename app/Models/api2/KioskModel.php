<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class KioskModel extends Model
{
  protected $table = 'kiosk';
	
	private $request;

	protected function initialize()
	{
		$this->request = service('request');
	}

  public function shopCheck()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select *, representative.name as representative_name from shop, representative where representative=representative.id and shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $shop_result = $shop_query->getResult();

          $resultVal['code'] = "100";
          $resultVal['shop_title'] = $shop_result['0']->title."";
          $resultVal['biz_num'] = $shop_result['0']->biznum."";
          $resultVal['representative_name'] = $shop_result['0']->representative_name."";
          $resultVal['shopimageversion'] = $shop_result['0']->shopimageversion."";
          $resultVal['imgpath'] = "http://".$_SERVER["HTTP_HOST"]."/image/shop/".$this->request->getGet('sid').".jpg";

          $kiosk_sql = "select * from kiosk where shop='".$this->request->getGet('sid')."' order by number";
          $kiosk_query = $this->db->query($kiosk_sql);
          $kiosk_result = $kiosk_query->getResult();

          $resultVal['kiosk_list'] = $kiosk_result;
        }
        else {
          $resultVal['code'] = "510";
          $resultVal['msg'] = "등록되지 않은 매장아이디";
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

  public function loadMenu1()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $resultVal['code'] = "100";

          $menu_sql = "select id, shop, title, price, takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=1 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
          $menu_query = $this->db->query($menu_sql);
          $menu_result = $menu_query->getResult();

          $resultVal['menu_list'] = $menu_result;
        }
        else {
          $resultVal['code'] = "510";
          $resultVal['msg'] = "등록되지 않은 매장아이디";
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

  public function loadMenu2()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $resultVal['code'] = "100";

          $menu_sql = "select id, shop, title, price, takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=2 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
          $menu_query = $this->db->query($menu_sql);
          $menu_result = $menu_query->getResult();

          $resultVal['menu_list'] = $menu_result;
        }
        else {
          $resultVal['code'] = "510";
          $resultVal['msg'] = "등록되지 않은 매장아이디";
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

  public function loadMenu3()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $resultVal['code'] = "100";

          $menu_sql = "select id, shop, title, price, takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=3 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
          $menu_query = $this->db->query($menu_sql);
          $menu_result = $menu_query->getResult();

          $resultVal['menu_list'] = $menu_result;
        }
        else {
          $resultVal['code'] = "510";
          $resultVal['msg'] = "등록되지 않은 매장아이디";
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






  public function menulist()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $sql = "select * from menu where shop='".$this->request->getGet('sid')."' and depth='1' order by sort";

          $query = $this->db->query($sql);

          $rows = $query->getNumRows();
          $result = $query->getResult();

          $menulist = array();

          for($i=0; $i<$rows; $i++) {
            $d1_menu = array();

            $d1_menu['menuid'] = $result[$i]->id;
            $d1_menu['menutitle'] = $result[$i]->title;
            $d1_menu['price'] = $result[$i]->price;
            $d1_menu['imagename'] = ($result[$i]->image?$result[$i]->id.".jpg":"");
            $d1_menu['imagepath'] = ($result[$i]->image?"/image/menu/".$result[$i]->id."/".$result[$i]->id.".jpg":"");
            $d1_menu['thumbimagename'] = ($result[$i]->thumbimage?"thumb_".$result[$i]->id.".jpg":"");
            $d1_menu['thumbimagepath'] = ($result[$i]->thumbimage?"/image/menu/".$result[$i]->id."/thumb_".$result[$i]->id.".jpg":"");
            $d1_menu['imageversion'] = $result[$i]->imageversion;
            $d1_menu['soldout'] = $result[$i]->soldout;

            $d2_sql = "select * from menu where shop='".$this->request->getGet('sid')."' and upperid='".$result[$i]->id."' and depth='2' order by sort";

            $d2_query = $this->db->query($d2_sql);

            $d2_rows = $d2_query->getNumRows();
            $d2_result = $d2_query->getResult();

            $menu2list = array();

            for($j=0; $j<$d2_rows; $j++) {
              $d2_menu = array();
            
              $d2_menu['menuid'] = $d2_result[$j]->id;
              $d2_menu['menutitle'] = $d2_result[$j]->title;
              $d2_menu['price'] = $d2_result[$j]->price;
              $d2_menu['imagename'] = ($d2_result[$j]->image?$d2_result[$j]->id.".jpg":"");
              $d2_menu['imagepath'] = ($d2_result[$j]->image?"/image/menu/".$d2_result[$j]->id."/".$d2_result[$j]->id.".jpg":"");
              $d2_menu['thumbimagename'] = ($d2_result[$j]->thumbimage?"thumb_".$d2_result[$j]->id.".jpg":"");
              $d2_menu['thumbimagepath'] = ($d2_result[$j]->thumbimage?"/image/menu/".$d2_result[$j]->id."/thumb_".$d2_result[$j]->id.".jpg":"");
              $d2_menu['imageversion'] = $d2_result[$j]->imageversion;
              $d2_menu['soldout'] = $d2_result[$j]->soldout;

              $d3_sql = "select * from menu where shop='".$this->request->getGet('sid')."' and upperid='".$d2_result[$j]->id."' and depth='3' order by sort";

              $d3_query = $this->db->query($d3_sql);

              $d3_rows = $d3_query->getNumRows();
              $d3_result = $d3_query->getResult();

              $menu3list = array();

              for($k=0; $k<$d3_rows; $k++) {
                $d3_menu = array();
              
                $d3_menu['menuid'] = $d3_result[$k]->id;
                $d3_menu['menutitle'] = $d3_result[$k]->title;
                $d3_menu['price'] = $d3_result[$k]->price;
                $d3_menu['imagename'] = ($d3_result[$k]->image?$d3_result[$k]->id.".jpg":"");
                $d3_menu['imagepath'] = ($d3_result[$k]->image?"/image/menu/".$d3_result[$k]->id."/".$d3_result[$k]->id.".jpg":"");
                $d3_menu['thumbimagename'] = ($d3_result[$k]->thumbimage?"thumb_".$d3_result[$k]->id.".jpg":"");
                $d3_menu['thumbimagepath'] = ($d3_result[$k]->thumbimage?"/image/menu/".$d3_result[$k]->id."/thumb_".$d3_result[$k]->id.".jpg":"");
                $d3_menu['imageversion'] = $d3_result[$k]->imageversion;
                $d3_menu['soldout'] = $d3_result[$k]->soldout;

                array_push($menu3list, $d3_menu);         
              }

              if(sizeof($menu3list)>0) {
                $d2_menu["menulist"] = $menu3list;
                $d2_menu["nextmenu"] = true;
              }
              else {
                $d2_menu["nextmenu"] = false;
              }

              array_push($menu2list, $d2_menu);              
            }

            if(sizeof($menu2list)>0) {
              $d1_menu["menulist"] = $menu2list;
              $d1_menu["nextmenu"] = true;
            }
            else {
              $d1_menu["nextmenu"] = false;
            }

            array_push($menulist, $d1_menu);
          }

          $resultVal["menulist"] = $menulist;
        }
        else {
          $resultVal['code'] = "510";
          $resultVal['msg'] = "등록되지 않은 매장아이디";
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