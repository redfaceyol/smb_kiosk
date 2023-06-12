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

  public function setBaseTabe()
  {
		$query = $this->db->query("create table if not exists payment_".date("Y")." like payment_base");
		$query = $this->db->query("create table if not exists order_".date("Y")." like order_base");
		$query = $this->db->query("create table if not exists orderdetail_".date("Y")." like orderdetail_base");
		$query = $this->db->query("create table if not exists pointhistory_".date("Y")." like pointhistory_base");
		$query = $this->db->query("create table if not exists kioskstatus_".date("Y")." like kioskstatus_base");
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
          $resultVal['tel'] = $shop_result['0']->tel."";
          $resultVal['address1'] = $shop_result['0']->address1."";
          $resultVal['address2'] = $shop_result['0']->address2."";
          $resultVal['shop_addinfo'] = $shop_result['0']->shop_addinfo."";
          $resultVal['representative_name'] = $shop_result['0']->representative_name."";
          $resultVal['shopimageversion'] = $shop_result['0']->shopimageversion."";
          if($shop_result['0']->shopimage) {
            $resultVal['imgpath'] = "http://".$_SERVER["HTTP_HOST"]."/image/shop/".$this->request->getGet('sid').".jpg";
          }
          else {
            $resultVal['imgpath'] = "";
          }

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

  public function loadCategory()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          if($this->request->getGet('kid')) {
            $kiosk_sql = "select * from kiosk where kiosk.id='".$this->request->getGet('kid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
    
            if($kiosk_rows) {
              $resultVal['code'] = "100";

              $category_sql = "select id, shop, title, sort, view, registe_datetime from category where view=1 and shop='".$this->request->getGet('sid')."' and kiosk='".$this->request->getGet('kid')."' order by sort";
              $category_query = $this->db->query($category_sql);
              $category_result = $category_query->getResult();

              $resultVal['category_list'] = $category_result;
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "501";
            $resultVal['msg'] = "입력된 키오스크아이디 없음";
          }
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

  public function loadMenu()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          if($this->request->getGet('kid')) {
            $kiosk_sql = "select * from kiosk where kiosk.id='".$this->request->getGet('kid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
    
            if($kiosk_rows) {
              $resultVal['code'] = "100";

              $menu_sql = "select menu.id, menu.shop, category, menu.title, if(isnull(price), '0', price) as price, if(isnull(takeoutprice), '0', takeoutprice) as takeoutprice, shopview, takeoutview, menu.sort, menu.view, imageversion, soldout, description, useoption, menu.registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', menu.id, '/', menu.id, '.jpg')) as imgpath from menu, category where category=category.id and menu.view=1 and menu.shop='".$this->request->getGet('sid')."' and kiosk='".$this->request->getGet('kid')."' order by sort";
              $menu_query = $this->db->query($menu_sql);
              $menu_result = $menu_query->getResult();

              $resultVal['menu_list'] = $menu_result;
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "501";
            $resultVal['msg'] = "입력된 키오스크아이디 없음";
          }
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

  public function loadOptiongroup()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          if($this->request->getGet('kid')) {
            $kiosk_sql = "select * from kiosk where kiosk.id='".$this->request->getGet('kid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
    
            if($kiosk_rows) {
              $resultVal['code'] = "100";
              
              $optiongroup_sql = "select optiongroup.id, optiongroup.shop, menu, optiongroup.title, choice, maxium, optiongroup.sort, duplication, optiongroup.registe_datetime from optiongroup, menu, category where category=category.id and menu=menu.id and optiongroup.shop='".$this->request->getGet('sid')."' and kiosk='".$this->request->getGet('kid')."' order by optiongroup.sort";
              $optiongroup_query = $this->db->query($optiongroup_sql);
              $optiongroup_result = $optiongroup_query->getResult();

              $resultVal['optiongroup_list'] = $optiongroup_result;
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "501";
            $resultVal['msg'] = "입력된 키오스크아이디 없음";
          }
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

  public function loadOption()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getGet('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          if($this->request->getGet('kid')) {
            $kiosk_sql = "select * from kiosk where kiosk.id='".$this->request->getGet('kid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
    
            if($kiosk_rows) {
              $resultVal['code'] = "100";

              $option_sql = "select option.id, option.shop, option.menu, optiongroup, option.title, option.price, option.sort, option.soldout, option.registe_datetime from option, menu, category where category=category.id and menu=menu.id and option.shop='".$this->request->getGet('sid')."' and kiosk='".$this->request->getGet('kid')."' order by option.sort";
              $option_query = $this->db->query($option_sql);
              $option_result = $option_query->getResult();

              $resultVal['option_list'] = $option_result;
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "501";
            $resultVal['msg'] = "입력된 키오스크아이디 없음";
          }
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

  public function saveSoldout()
  {
    $this->setBaseTabe();

    $resultVal = array();

    try {
      if($this->request->getPost('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getPost('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          $resultVal['code'] = "100";

          if($this->request->getPost('soldout')) {
            $tmp_soldout = explode("┼", $this->request->getPost('soldout'));

            foreach($tmp_soldout as $soldout_item) {
              $tmp_soldout_item = explode("|", $soldout_item);

              if($tmp_soldout_item[0] == "menu") {
                $sql = "update menu set soldout='".$tmp_soldout_item[2]."' where id='".$tmp_soldout_item[1]."'";
              }
              else if($tmp_soldout_item[0] == "option") {
                $sql = "update option set soldout='".$tmp_soldout_item[2]."' where id='".$tmp_soldout_item[1]."'";
              }

              $this->db->query($sql);
            }
          }
          else {
            $resultVal['code'] = "520";
            $resultVal['msg'] = "품절 정보가 없음";
          }
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

  public function saveKioskStatus()
  {
    $this->setBaseTabe();

    $resultVal = array();

    try {
      if($this->request->getPost('sid')) {
        $shop_sql = "select * from shop where shop.id='".$this->request->getPost('sid')."'";
        $shop_query = $this->db->query($shop_sql);
        $shop_rows = $shop_query->getNumRows();

        if($shop_rows) {
          if($this->request->getPost('kioskid')) {
            $kiosk_sql = "select * from kiosk where kiosk.id='".$this->request->getPost('kioskid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();

            if($kiosk_rows) {
              $resultVal['code'] = "100";
                            
              $builder = $this->db->table("kioskstatus_".date("Y"));

              $data_kioskstatus = [
                'shop' => $this->request->getPost('sid'), 
                'kiosk' => $this->request->getPost('kioskid'), 
                'total_sapce' => $this->request->getPost('totalSize'), 
                'used_space' => $this->request->getPost('usedSize'), 
                'kiosk_version' => $this->request->getPost('version'), 
              ];
              
              $builder->set('registe_time', "now()", false);
              $builder->set('registe_date', "now()", false);
              $builder->set($data_kioskstatus);
              $builder->insert();

              $builder = $this->db->table("kiosk");

              $data_kiosk = array();
              if($this->request->getPost('version')) {
                $data_kiosk["kiosk_version"] = $this->request->getPost('version');
              }
              if($this->request->getPost('totalSize')) {
                $data_kiosk["total_space"] = $this->request->getPost('totalSize');
              }
              if($this->request->getPost('usedSize')) {
                $data_kiosk["used_space"] = $this->request->getPost('usedSize');
              }
              
              $builder->set('lastupdate_datetime', "now()", false);		
              $builder->where('id', $this->request->getPost('kioskid'));
              $builder->update($data_kiosk);
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "501";
            $resultVal['msg'] = "입력된 키오스크아이디 없음";
          }
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





  /*
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

          $menu_sql = "select id, shop, title, if(isnull(price), '0', price) as price, if(isnull(takeoutprice), '0', takeoutprice) as takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=1 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
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

          $menu_sql = "select id, shop, title, if(isnull(price), '0', price) as price, if(isnull(takeoutprice), '0', takeoutprice) as takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=2 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
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

          $menu_sql = "select id, shop, title, if(isnull(price), '0', price) as price, if(isnull(takeoutprice), '0', takeoutprice) as takeoutprice, sort, view, depth, imageversion, soldout, upperid, registe_datetime, if(isnull(image), '', concat('http://".$_SERVER["HTTP_HOST"]."/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where depth=3 and view=1 and shop='".$this->request->getGet('sid')."' order by sort";
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
  */
}
?>