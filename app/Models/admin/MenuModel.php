<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class MenuModel extends Model
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

		$sql = "select shop.*, (select count(id) from menu where shop=shop.id and depth='1') as menu1cnt, (select count(id) from menu where shop=shop.id and depth='2') as menu2cnt from shop where 1=1";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (title like '%".$this->request->getGet('searchtext')."%' or shop.id like '%".$this->request->getGet('searchtext')."%') ";
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

	public function getMenuList()
	{
		$sql = "select id, shop, title, price, sort, view, depth, imageversion, upperid, image, takeoutprice from menu where shop='".$this->request->getGet('sid')."' and depth='1' order by sort";

		$query = $this->db->query($sql);

    $rows = $query->getNumRows();
    $result = $query->getResult();

		$menulist = array();

		for($i=0; $i<$rows; $i++) {
			array_push($menulist, $result[$i]);

			$d2_sql = "select id, shop, title, price, sort, view, depth, imageversion, upperid, image, takeoutprice from menu where shop='".$this->request->getGet('sid')."' and upperid='".$result[$i]->id."' and depth='2' order by sort";

			$d2_query = $this->db->query($d2_sql);

			$d2_rows = $d2_query->getNumRows();
			$d2_result = $d2_query->getResult();

			for($j=0; $j<$d2_rows; $j++) {
				array_push($menulist, $d2_result[$j]);

				$d3_sql = "select id, shop, title, price, sort, view, depth, imageversion, upperid, image, takeoutprice from menu where shop='".$this->request->getGet('sid')."' and upperid='".$d2_result[$j]->id."' and depth='3' order by sort";

				$d3_query = $this->db->query($d3_sql);

				$d3_rows = $d3_query->getNumRows();
				$d3_result = $d3_query->getResult();

				for($k=0; $k<$d3_rows; $k++) {
					array_push($menulist, $d3_result[$k]);					
				}
			}
		}

		$returnVal["list"] = $menulist;

		//print_r($menulist);exit;
 
		return $returnVal;
	}

	public function getImage($inId)
	{
		$sql = "select image from menu where id='".$inId."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		return $result[0]->image;
	}

	public function getThumbImage($inId)
	{
		$sql = "select thumbimage from menu where id='".$inId."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		return $result[0]->thumbimage;
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

	public function postMenu($imgdata)
	{
    $builder = $this->db->table('menu');

		$max_sort_query = $this->db->query("select ifnull(max(sort)+1, 1) as max_sort from menu where shop='".$this->request->getPost('sid')."' and depth='".$this->request->getPost('depth')."'");
		$max_sort_result = $max_sort_query->getResult();
		$max_sort = $max_sort_result["0"]->max_sort;

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'title' => $this->request->getPost('title'), 
      'price' => $this->request->getPost('price'),
			'takeoutprice' => $this->request->getPost('takeoutprice'),
			'sort' => $max_sort, 
			'view' => '1', 
			'depth' => $this->request->getPost('depth'), 
			'imageversion' => time(), 
			'upperid' => $this->request->getPost('uid'), 
		];
    
		if(isset($imgdata["imagefile"]) && $imgdata["imagefile"]["upload_data"]->getTempName()) {
			$imagesize = getimagesize($imgdata["imagefile"]["upload_data"]->getTempName());

			$org_width = $imagesize[0];
			$org_height = $imagesize[1];

      $trg_width = 600;
      $trg_height = 600;

      $trg2_width = 256;
      $trg2_height = 256;

			$target = imagecreatetruecolor($trg_width, $trg_height);
			$target2 = imagecreatetruecolor($trg2_width, $trg2_height);

			if($imagesize[2] == 1)
			{
				$source = imagecreatefromgif($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagegif($target);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagegif($target2);
				$imgdata_bn2 = ob_get_clean();
			}
			else if($imagesize[2] == 2)
			{
				$source = imagecreatefromjpeg($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target, null, 100);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target2, null, 100);
				$imgdata_bn2 = ob_get_clean();
			}
			else if($imagesize[2] == 3)
			{
				$source = imagecreatefrompng($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagepng($target, null, 0);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagepng($target2, null, 0);
				$imgdata_bn2 = ob_get_clean();
			}

			imagedestroy($source);
			imagedestroy($target);
			imagedestroy($target2);

			$data['image'] = $imgdata_bn;
			$data['thumbimage'] = $imgdata_bn2;
		}

    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|메뉴관리|등록되었습니다.');

		$this->response->redirect("/admin/menu/menuList?sid=".$this->request->getPost('sid'));
	}

	public function getMenuData()
	{
		$returnVal = null;

		$sql = "select menu.* from menu where id='".$this->request->getGet('oid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}

		return $returnVal;
	}

	public function putMenu($imgdata)
	{
    $builder = $this->db->table('menu');

		$data = [
			'title' => $this->request->getPost('title'), 
      'price' => $this->request->getPost('price'),
			'takeoutprice' => $this->request->getPost('takeoutprice'),
		];
    
		if(isset($imgdata["imagefile"]) && $imgdata["imagefile"]["upload_data"]->getTempName()) {
			$imagesize = getimagesize($imgdata["imagefile"]["upload_data"]->getTempName());

			$org_width = $imagesize[0];
			$org_height = $imagesize[1];

      $trg_width = 600;
      $trg_height = 600;

      $trg2_width = 256;
      $trg2_height = 256;

			$target = imagecreatetruecolor($trg_width, $trg_height);
			$target2 = imagecreatetruecolor($trg2_width, $trg2_height);

			if($imagesize[2] == 1)
			{
				$source = imagecreatefromgif($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagegif($target);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagegif($target2);
				$imgdata_bn2 = ob_get_clean();
			}
			else if($imagesize[2] == 2)
			{
				$source = imagecreatefromjpeg($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target, null, 100);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagejpeg($target2, null, 100);
				$imgdata_bn2 = ob_get_clean();
			}
			else if($imagesize[2] == 3)
			{
				$source = imagecreatefrompng($imgdata["imagefile"]["upload_data"]->getTempName());
				imagecopyresized($target, $source, 0, 0, 0, 0, $trg_width, $trg_height, $org_width, $org_height);
				ob_start();
				imagepng($target, null, 0);
				$imgdata_bn = ob_get_clean();
				imagecopyresized($target2, $source, 0, 0, 0, 0, $trg2_width, $trg2_height, $org_width, $org_height);
				ob_start();
				imagepng($target2, null, 0);
				$imgdata_bn2 = ob_get_clean();
			}

			imagedestroy($source);
			imagedestroy($target);
			imagedestroy($target2);

			$data['imageversion'] = time();
			$data['image'] = $imgdata_bn;
			$data['thumbimage'] = $imgdata_bn2;
		}
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);

		$this->session->setFlashdata('message', 'primary|메뉴관리|수정되었습니다.');

		$this->response->redirect("/admin/menu/menuModify?sid=".$this->request->getPost('sid')."&oid=".$this->request->getPost('oid')."&cid=".md5($this->request->getPost('oid')));
	}

	public function delMenu()
	{
    $builder = $this->db->table('menu');

		$builder->where('id', $this->request->getGet('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|메뉴관리|삭제되었습니다.');

		$this->response->redirect("/admin/menu/menuList?sid=".$this->request->getGet('sid')."&page=".$this->request->getGet('page'));
	}
}
?>