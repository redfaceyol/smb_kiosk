<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class ShopModel extends Model
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

		$sql = "select shop.*, representative.name as representative_name, (select count(kiosk.id) from kiosk where shop=shop.id) as cntKiosk from shop, representative where representative=representative.id ";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (title like '%".$this->request->getGet('searchtext')."%' or shop.id like '%".$this->request->getGet('searchtext')."%' or tel like '%".$this->request->getGet('searchtext')."%') ";
    }

		if($this->session->get('member_grade') == "50") {
			$sql .= " and representative.id='".$this->session->get('member_id')."' ";
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

  public function findRepresentative()
  {
		$sql = "select * from representative where (id like '%".$this->request->getPost('searchrepresentative')."%' OR name like '%".$this->request->getPost('searchrepresentative')."%') order by name";

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

	public function selectRepresentative()
	{
		$sql = "select * from representative where id='".$this->request->getPost('selectrepresentative')."'";

		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		$resultVal = array();

		if($rows) {
			$result = $query->getResult();

			$resultVal["status"] = "OK";
			$resultVal["result"] = $result;
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

      $query = $this->db->query("select id from shop where id='".$genid."'");

      if($query->getNumRows() == 0)
      {
        $rslt = false;
      }
    }

    return $genid;
  }

	public function postShop($imgdata)
	{
    $builder = $this->db->table('shop');

		$shopId = $this->generateId();

		$data = [
			'id' => $shopId, 
			'title' => $this->request->getPost('title'), 
      'representative' => $this->request->getPost('representative'),
			'tel' => $this->request->getPost('tel'), 
			'zipcode' => $this->request->getPost('zipcode'), 
			'address1' => $this->request->getPost('address1'), 
			'address2' => $this->request->getPost('address2'), 
			'biznum' => $this->request->getPost('biznum'), 
			'shop_addinfo' => $this->request->getPost('shop_addinfo'), 
			'status' => '1', 
		];
    
		if(isset($imgdata["imagefile"]) && $imgdata["imagefile"]["upload_data"]->getTempName()) {
			$imagesize = getimagesize($imgdata["imagefile"]["upload_data"]->getTempName());

			$org_width = $imagesize[0];
			$org_height = $imagesize[1];

      $trg_width = 1080;
      $trg_height = 1676;

			list($trg_width, $trg_height) = colImageSize($org_width, $org_height, $trg_width, $trg_height);

			$target = imagecreatetruecolor($trg_width, $trg_height);

			if($imagesize[2] == 1)
			{
				$source = imagecreatefromgif($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagegif($target);
				$imgdata_bn = ob_get_clean();
			}
			else if($imagesize[2] == 2)
			{
				$source = imagecreatefromjpeg($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target, null, 100);
				$imgdata_bn = ob_get_clean();
			}
			else if($imagesize[2] == 3)
			{
				$source = imagecreatefrompng($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagepng($target, null, 0);
				$imgdata_bn = ob_get_clean();
			}

			imagedestroy($source);
			imagedestroy($target);

			$data['shopimage'] = $imgdata_bn;
			$data['shopimageversion'] = time();
		}

		$builder->set('password', "password('".$this->request->getPost('password')."')", false);
    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		for($i=0; $i<$this->request->getPost('kiosk'); $i++) {
			
			$builder = $this->db->table('kiosk');

			$data = [
				'id' => $this->generateKioskId(), 
				'shop' => $shopId,
				'number' => ($i+1), 
				'types' => '1', 
				'status' => '1', 
			];
	
			$builder->set('registe_datetime', "now()", false);
			$builder->set($data);
			$builder->insert();
		}

		$this->session->setFlashdata('message', 'primary|업장관리|등록되었습니다.');

		$this->response->redirect("/admin/shop/shopList");
	}

  public function generateKioskId()
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

	public function getShopData()
	{
		$returnVal = null;

		$sql = "select shop.*, representative.name as representative_name from shop, representative where representative=representative.id and shop.id='".$this->request->getGet('oid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}

		return $returnVal;
	}

	public function putShop($imgdata)
	{
    $builder = $this->db->table('shop');

		$data = [
			'title' => $this->request->getPost('title'), 
      'representative' => $this->request->getPost('representative'),
			'tel' => $this->request->getPost('tel'), 
			'zipcode' => $this->request->getPost('zipcode'), 
			'address1' => $this->request->getPost('address1'), 
			'address2' => $this->request->getPost('address2'), 
			'biznum' => $this->request->getPost('biznum'), 
			'shop_addinfo' => $this->request->getPost('shop_addinfo'), 
		];
    
		if(isset($imgdata["imagefile"]) && $imgdata["imagefile"]["upload_data"]->getTempName()) {
			$imagesize = getimagesize($imgdata["imagefile"]["upload_data"]->getTempName());

			$org_width = $imagesize[0];
			$org_height = $imagesize[1];

      $trg_width = 1080;
      $trg_height = 1676;

			list($trg_width, $trg_height) = colImageSize($org_width, $org_height, $trg_width, $trg_height);
			
			$target = imagecreatetruecolor($trg_width, $trg_height);

			if($imagesize[2] == 1)
			{
				$source = imagecreatefromgif($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagegif($target);
				$imgdata_bn = ob_get_clean();
			}
			else if($imagesize[2] == 2)
			{
				$source = imagecreatefromjpeg($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target, null, 100);
				$imgdata_bn = ob_get_clean();
			}
			else if($imagesize[2] == 3)
			{
				$source = imagecreatefrompng($imgdata["imagefile"]["upload_data"]->getTempName());
				ImageCopyResampled($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagepng($target, null, 0);
				$imgdata_bn = ob_get_clean();
			}

			imagedestroy($source);
			imagedestroy($target);

			$data['shopimage'] = $imgdata_bn;
			$data['shopimageversion'] = time();
		}

		if($this->request->getPost('password')) {
			$builder->set('password', "password('".$this->request->getPost('password')."')", false);
		}
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);

		if($this->request->getPost('kioskpassword')) {
			$key = hash( 'sha256', "smbkiosk_key" );
			$iv = substr( hash( 'sha256', "smbkiosk_iv" ), 0, 16 );

			$builder = $this->db->table('kiosk');
			$data = array();
			$data = [
				'kioskpassword' => base64_encode( openssl_encrypt( $this->request->getPost('kioskpassword'), "AES-256-CBC", $key, 0, $iv ) ),
			];
			$builder->where('shop', $this->request->getPost('oid'));
			$builder->update($data);
		}

		$this->session->setFlashdata('message', 'primary|업장관리|수정되었습니다.');

		$this->response->redirect("/admin/shop/shopModify?oid=".$this->request->getPost('oid')."&cid=".md5($this->request->getPost('oid')));
	}

	public function delShop()
	{
    $builder = $this->db->table('shop');

		$builder->where('id', $this->request->getGet('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|업장관리|삭제되었습니다.');

		$this->response->redirect("/admin/shop/shopList?page=".$this->request->getGet('page'));
	}
}
?>