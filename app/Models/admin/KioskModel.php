<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class KioskModel extends Model
{
  protected $table = 'member';
	
	private $request;
	private $response;
	private $session;

	protected function initialize()
	{
		$this->request = service('request');
		$this->response = service('response');
		$this->session = session();
	}

	public function getShopList()
	{
			$page_per_block = 10;
			$num_per_page = 10;

			$sql = "select shop.*, (select count(*) from kiosk where shop=shop.id) as kioskcnt, (select count(*) from kiosk B where (kiosk_version='' or kiosk_version is null) and shop = shop.id) as kioskStatus0, (select count(*) from kiosk B where (kiosk_version<>'' and kiosk_version is not null) and shop = shop.id) as kioskStatus1 from shop where 1=1";

			if($this->request->getGet('searchtext')) {
				$sql .= " and (title like '%".$this->request->getGet('searchtext')."%' or shop.id like '%".$this->request->getGet('searchtext')."%') ";
			}

			if($this->session->get('member_grade') == "50") {
				$sql .= " and representative='".$this->session->get('member_id')."' ";
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

					$this->response->redirect("/admin/shop/shopList?".$_Link);

					exit;
			}

			if( $page > $total_page ){
					$page = $total_page;
			}

			$total_count = $rows;

			$sql = "select * from ( ".$sql." ) A order by registe_datetime desc";

			$sql .= " limit ".$first.", ".$num_per_page;

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

	public function getKioskList()
	{
		$page_per_block = 10;
		$num_per_page = 10;

		$sql = "select A.*, shop.title as shop_title, count(*) as kioskCnt, (select count(*) from kiosk B where status='0' and A.shop=B.shop) as kioskStatus0, (select count(*) from kiosk B where status='1' and A.shop=B.shop) as kioskStatus1 from kiosk A, shop where shop=shop.id ";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (shop.title like '%".$this->request->getGet('searchtext')."%') ";
    }

		$sql .= " group by shop";

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
		$sql = "select id, password, title, region, representative, tel, zipcode, address1, address2, biznum, status, registe_datetime, shopimageversion from shop where (id like '%".$this->request->getPost('searchshop')."%' OR title like '%".$this->request->getPost('searchshop')."%') order by title";

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
		$sql = "select id, password, title, region, representative, tel, zipcode, address1, address2, biznum, status, registe_datetime, shopimageversion from shop where id='".$this->request->getPost('selectshop')."'";

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
		/*
		$data = [
			'id' => $this->generateId(), 
      'shop' => $this->request->getPost('shop'),
			'number' => $this->request->getPost('kiosknumber'), 
			'types' => $this->request->getPost('kiosktype'), 
			'status' => '1', 
		];
		*/
		/*
		$rslt = true;
		$gennumber = 0;

    while($rslt) {
			$gennumber++;

      $query = $this->db->query("select id from kiosk where shop='".$this->request->getGet('sid')."' and number='".$gennumber."'");

      if($query->getNumRows() == 0)
      {
        $rslt = false;
      }
    }
		*/
		$max_number_query = $this->db->query("select ifnull(max(number)+1, 1) as max_number from kiosk where shop='".$this->request->getGet('sid')."'");
		$max_number_result = $max_number_query->getResult();
		$max_number = $max_number_result["0"]->max_number;

		$data = [
			'id' => $this->generateId(), 
      'shop' => $this->request->getGet('sid'),
			'number' => $max_number, 
			'types' => '1', 
			'status' => '1', 
		];

    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|KIOSK관리|등록되었습니다.');

		$this->response->redirect("/admin/kiosk/kioskManage?sid=".$this->request->getGet('sid')."&page=".$this->request->getGet('page'));
	}

	public function getKioskAllData()
	{
		$returnVal = null;

		$sql = "select kiosk.*, shop.title as shop_title, (select max(kiosk_version) from kiosk) as maxVersion, (SELECT COUNT(id) FROM category WHERE shop = shop.id AND kiosk = kiosk.id) AS existMenu from kiosk, shop where shop=shop.id and shop='".$this->request->getGet('sid')."' order by kiosk.number";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();
		
		$returnVal["list"] = array();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal["list"] = $row;
		}

		$sql2 = "select title from shop where id='".$this->request->getGet('sid')."'";
		$query2 = $this->db->query($sql2);
		$rows2 = $query2->getNumRows();

		if($rows2) {
			$row2 = $query2->getResultArray();

			$returnVal["shop_title"] = $row2[0]["title"];
		}

		return $returnVal;
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

		$this->response->redirect("/admin/kiosk/kioskManage?sid=".$this->request->getGet('sid')."&page=".$this->request->getGet('page'));
	}
}
?>