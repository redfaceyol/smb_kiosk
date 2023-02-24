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

  public function kiosk_login()
  {
    $resultVal = array();

    try {
      if($this->request->getGet('sid')) {
        if($this->request->getGet('kid')) {

          $shop_sql = "select *, representative.name as representative_name from shop, representative where representative=representative.id and shop.id='".$this->request->getGet('sid')."'";
          $shop_query = $this->db->query($shop_sql);
          $shop_rows = $shop_query->getNumRows();

          if($shop_rows) {
            $kiosk_sql = "select * from kiosk where shop='".$this->request->getGet('sid')."' and id='".$this->request->getGet('kid')."'";
            $kiosk_query = $this->db->query($kiosk_sql);
            $kiosk_rows = $kiosk_query->getNumRows();
  
            if($kiosk_rows) {
              $shop_result = $shop_query->getResult();
              $kiosk_result = $kiosk_query->getResult();

              $resultVal['code'] = "100";
              $resultVal['id'] = $kiosk_result['0']->id."";
              $resultVal['kiosk_type'] = $kiosk_result['0']->types."";
              $resultVal['shop_id'] = $shop_result['0']->id."";
              $resultVal['shop_title'] = $shop_result['0']->title."";
              $resultVal['biz_num'] = $shop_result['0']->biznum."";
              $resultVal['representative_name'] = $shop_result['0']->representative_name."";
              $resultVal['sign_image'] = ($shop_result['0']->signimage?"/image/sign/".$shop_result['0']->id."/".$shop_result['0']->id.".jpg":"");
            }
            else {
              $resultVal['code'] = "511";
              $resultVal['msg'] = "등록되지 않은 키오스크아이디";
            }
          }
          else {
            $resultVal['code'] = "510";
            $resultVal['msg'] = "등록되지 않은 매장아이디";
          }

        }
        else {
          $resultVal['code'] = "501";
          $resultVal['msg'] = "입력된 키오스크아이디 없음";
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






	public function getKioskList()
	{
		$page_per_block = 10;
		$num_per_page = 10;

		$sql = "select kiosk.*, shop.title as shop_title from kiosk, shop where shop=shop.id ";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (shop.title like '%".$this->request->getGet('searchtext')."%') ";
    }

		$query = $this->db->query($sql);
    $rows = $query->getNumRows();

		if( !$this->request->getGet('page') || $this->request->getGet('page') < 1 ){
			$page = 1;
		}
		else {
			$page = $this->request->getGet('page');
		}

		if( !$rows ){
			$first = 1;
			$last  = 0;
		}
		else {
			$first = $num_per_page * ($page - 1);
			$last  = $num_per_page * $page;

			$isnext = $rows - $last;

			if($isnext > 0){
				$last -= 1;
			}
			else{
				$last = $rows - 1;
			}
		}

		$total_page = ceil( $rows / $num_per_page );
		$total_block = ceil( $total_page / $page_per_block );
		$block = ceil( $page / $page_per_block );

		$first_page = ( $block - 1 ) * $page_per_block;
		$last_page = $block * $page_per_block;

		if( $total_block <= $block ){
			$last_page = $total_page;
		}
		
		if($page > $last_page && $last_page > 0) {
			$_Link = ""."&page=".$last_page;

			$this->response->redirect("/admin/kiosk/kioskList?".$_Link);

			exit;
		}

		if( $page > $total_page ){
			$page = $total_page;
		}

		$total_count = $rows;

		$sql = "select * from ( ".$sql." ) A order by registe_datetime desc";

		$sql .= " limit	".$first.", ".$num_per_page;

    $query = $this->db->query($sql);
    $result = $query->getResult();

		$returnVal["list"] = $result;

		$returnVal["page_per_block"] = $page_per_block;
		$returnVal["num_per_page"] = $num_per_page;
		$returnVal["total_page"] = $total_page;
		$returnVal["first_page"] = $first_page;
		$returnVal["last_page"] = $last_page;
		$returnVal["cur_page"] = $page;
		$returnVal["cur_block"] = $block;
		$returnVal["total_count"] = $total_count;
 
		return $returnVal;
	}

	public function checkID()
	{
		$query = $this->db->query("select id from ".$this->request->getPost('TypeofUser')." where id='".$this->request->getPost('id')."'");

		if($query->getNumRows() > 0)
		{
			$returnVal["status"] = "fail";
		}
		else 
		{
			$returnVal["status"] = "OK";
		}

		return $returnVal;
	}

  public function findShop()
  {
		$sql = "select * from shop where (id like '%".$this->request->getPost('searchshop')."%' OR title like '%".$this->request->getPost('searchshop')."%') order by title";

		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

    $result = null;

		if($rows) {
			$result = $query->getResult();
		}

		$resultVal["status"] = "OK";
		$resultVal["list"] = $result;

		return $resultVal;
  }

	public function selectShop()
	{
		$sql = "select * from shop where id='".$this->request->getPost('selectshop')."'";

		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		$resultVal = array();

		if($rows) {
			$result = $query->getResult();

			$resultVal["status"] = "OK";
			$resultVal["result"] = $result;
				
			$kiosk_sql = "select * from kiosk where shop='".$this->request->getPost('selectshop')."' order by number";

			$kiosk_query = $this->db->query($kiosk_sql);
			$kiosk_rows = $kiosk_query->getNumRows();
			
			$resultVal["kiosk_number"] = [];

			if($kiosk_rows) {
				$kiosk_result = $kiosk_query->getResult();

				$resultVal["kiosk_number"] = $kiosk_result;
			}
		}
		else {
			$resultVal["status"] = "fail";
		}

		return $resultVal;
	}

  public function generateId()
  {
    $rslt = true;

    while($rslt) {
      $genid = rand(10000,99999);

      $query = $this->db->query("select id from kiosk where id='".$genid."'");

      if($query->getNumRows() == 0)
      {
        $rslt = false;
      }
    }

    return $genid;
  }

	public function postKiosk()
	{
    $builder = $this->db->table('kiosk');

		$data = [
			'id' => $this->generateId(), 
      'shop' => $this->request->getPost('shop'),
			'number' => $this->request->getPost('kiosknumber'), 
			'types' => $this->request->getPost('kiosktype'), 
			'status' => '1', 
		];

    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|KIOSK관리|등록되었습니다.');

		$this->response->redirect("/admin/kiosk/kioskList");
	}

	public function getKioskData()
	{
		$returnVal = null;

		$sql = "select kiosk.*, shop.title as shop_title from kiosk, shop where shop=shop.id and kiosk.id='".$this->request->getGet('oid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}
				
		$kiosk_sql = "select * from kiosk where shop='".$returnVal["shop"]."' order by number";

		$kiosk_query = $this->db->query($kiosk_sql);
		$kiosk_rows = $kiosk_query->getNumRows();
		
		$returnVal["kiosk_number"] = [];

		if($kiosk_rows) {
			$kiosk_result = $kiosk_query->getResult();

			$returnVal["kiosk_number"] = $kiosk_result;
		}

		return $returnVal;
	}

	public function putKiosk()
	{
    $builder = $this->db->table('kiosk');

		$data = [
      'shop' => $this->request->getPost('shop'),
			'number' => $this->request->getPost('kiosknumber'), 
			'types' => $this->request->getPost('kiosktype'), 
		];
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);



		$this->session->setFlashdata('message', 'primary|KIOSK관리|수정되었습니다.');

		$this->response->redirect("/admin/kiosk/kioskModify?oid=".$this->request->getPost('oid')."&cid=".md5($this->request->getPost('oid')));
	}

	public function delKiosk()
	{
    $builder = $this->db->table('kiosk');

		$builder->where('id', $this->request->getGet('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|KIOSK관리|삭제되었습니다.');

		$this->response->redirect("/admin/kiosk/kioskList?page=".$this->request->getGet('page'));
	}
}
?>