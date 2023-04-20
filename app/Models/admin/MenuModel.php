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

			$sql = "select shop.*, (select count(id) from category where shop=shop.id ) as categorycnt, (select count(id) from menu where shop=shop.id) as menucnt from shop where 1=1";

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

	public function ajaxGetMenus()
	{
		$sql = "select * from category where shop='".$this->request->getPost('sid')."' order by sort";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		$returnVal["status"] = "OK";

		$arrList = array();

		if($rows){
			$result = $query->getResult();

			for($i=0; $i<$rows; $i++) {
				$sql2 = "select * from menu where shop='".$this->request->getPost('sid')."' and category='".$result[$i]->id."' order by sort";
				$query2 = $this->db->query($sql2);
				$rows2 = $query2->getNumRows();
				
				$menus = array();

				if($rows2){
					$result2 = $query2->getResult();
		
					for($j=0; $j<$rows2; $j++) {
						array_push($menus, array('text' => $result2[$j]->title, 'tag' => 'menu|'.$result2[$j]->id));
					}
				}

				array_push($menus, array('text' => "메뉴추가", 'tag' => 'menu-add|'.$result[$i]->id, 'icon' => 'bx bx-edit-alt', 'backColor' => '#bebebe', 'color' => '#fafafa'));

				array_push($arrList, array('text' => $result[$i]->title, 'tag' => 'category|'.$result[$i]->id, 'nodes' => $menus));
			}
		}

		array_push($arrList, array('text' => "카테고리추가", 'tag' => 'category-add', 'icon' => 'bx bx-edit-alt', 'backColor' => '#a2a2a2', 'color' => '#fafafa'));

		$returnVal["list"] = $arrList;

		return $returnVal;
	}

	public function postCategory()
	{
    $builder = $this->db->table('category');

		$max_sort_query = $this->db->query("select ifnull(max(sort)+1, 1) as max_sort from category where shop='".$this->request->getPost('sid')."'");
		$max_sort_result = $max_sort_query->getResult();
		$max_sort = $max_sort_result["0"]->max_sort;

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'title' => $this->request->getPost('category_title'),
			'sort' => $max_sort, 
			'view' => '1', 
		];

    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'primary|메뉴관리|등록되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getPost('sid')."&".$_Link);
	}

	public function ajaxLoadCategory() 
	{
		$sql = "select *, (select count(id) from menu where category=category.id) as menu_cnt, md5(id) as ccid from category where shop='".$this->request->getPost('sid')."' and id='".$this->request->getPost('cid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();
		$result = null;

		$resultVal["status"] = "OK";

		if($rows) {
			$result = $query->getResult();
		}

		$resultVal["data"] = $result[0];

		return $resultVal;
	}

	public function putCategory()
	{
    $builder = $this->db->table('category');

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'title' => $this->request->getPost('category_title'), 
		];
		
		$builder->where('id', $this->request->getPost('cid'));
		$builder->update($data);

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'primary|카테고리관리|수정되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getPost('sid')."&".$_Link);
	}

	public function delCategory()
	{
    $builder = $this->db->table('category');

		$builder->where('id', $this->request->getGet('cid'));
		$builder->delete();

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'danger|카테고리관리|삭제되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getGet('sid')."&".$_Link);
	}

	public function postMenu($imgdata)
	{
    $builder = $this->db->table('menu');

		$max_sort_query = $this->db->query("select ifnull(max(sort)+1, 1) as max_sort from menu where shop='".$this->request->getPost('sid')."'");
		$max_sort_result = $max_sort_query->getResult();
		$max_sort = $max_sort_result["0"]->max_sort;

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'category' => $this->request->getPost('cid'), 
			'title' => $this->request->getPost('title'), 
      'price' => $this->request->getPost('price'),
      'shopview' => $this->request->getPost('shop_view'),
			'takeoutprice' => $this->request->getPost('takeoutprice'),
			'takeoutview' => $this->request->getPost('takeout_view'),
			'description' => $this->request->getPost('description'),
			'sort' => $max_sort, 
			'view' => '1', 
			'imageversion' => time(), 
			'useoption' => $this->request->getPost('use_option'),
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

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'primary|메뉴관리|등록되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getPost('sid')."&".$_Link);
	}

	public function ajaxLoadMenu() 
	{
		$sql = "select id, shop, category, title, price, takeoutprice, shopview, takeoutview, sort, view, imageversion, soldout, description, useoption, registe_datetime, (select id from category where category=category.id) as category_id, (select title from category where category=category.id) as category_title, md5(id) as cmid, if(isnull(image), '', concat('/image/menu/', id, '/', id, '.jpg')) as imgpath from menu where shop='".$this->request->getPost('sid')."' and id='".$this->request->getPost('mid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();
		$result = null;

		$resultVal["status"] = "OK";

		if($rows) {
			$result = $query->getResult();
		}

		$resultVal["data"] = $result[0];

		return $resultVal;
	}

	public function putMenu($imgdata)
	{
    $builder = $this->db->table('menu');

		$data = [
			'title' => $this->request->getPost('title'), 
      'price' => $this->request->getPost('price'),
      'shopview' => $this->request->getPost('shop_view'),
			'takeoutprice' => $this->request->getPost('takeoutprice'),
			'takeoutview' => $this->request->getPost('takeout_view'),
			'description' => $this->request->getPost('description'),
			'useoption' => $this->request->getPost('use_option'),
		];

		if($this->request->getPost('delimage')) {
			$data['imageversion'] = time();
			$data['image'] = null;
			$data['thumbimage'] = null;
		}
    
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
		
		$builder->where('id', $this->request->getPost('mid'));
		$builder->update($data);

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'primary|메뉴관리|수정되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getPost('sid')."&".$_Link);
	}

	public function delMenu()
	{
		$sql = "select * from optiongroup where shop='".$this->request->getGet('sid')."' and menu='".$this->request->getGet('mid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$result = $query->getResult();

			for($i=0; $i<$rows; $i++) {				
				$builder = $this->db->table('option');
	
				$builder->where(array('shop' => $this->request->getGet('sid'), 'menu' => $this->request->getGet('mid'), 'optiongroup' => $result[$i]->id));
				$builder->delete();
			}

			$builder = $this->db->table('optiongroup');

			$builder->where(array('shop' => $this->request->getGet('sid'), 'menu' => $this->request->getGet('mid')));
			$builder->delete();
		}

    $builder = $this->db->table('menu');

		$builder->where('id', $this->request->getGet('mid'));
		$builder->delete();

		$_Link = "page=".$this->request->getGet('page');

		$this->session->setFlashdata('message', 'danger|메뉴관리|삭제되었습니다.');

		$this->response->redirect("/admin/menu/menuManage?sid=".$this->request->getGet('sid')."&".$_Link);
	}

	public function ajaxGetOptions()
	{
		$sql = "select * from optiongroup where shop='".$this->request->getPost('sid')."' and menu='".$this->request->getPost('mid')."' order by sort";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		$returnVal["status"] = "OK";

		$arrList = array();

		if($rows){
			$result = $query->getResult();

			for($i=0; $i<$rows; $i++) {
				$sql2 = "select * from option where shop='".$this->request->getPost('sid')."' and menu='".$this->request->getPost('mid')."' and optiongroup='".$result[$i]->id."' order by sort";
				$query2 = $this->db->query($sql2);
				$rows2 = $query2->getNumRows();
				
				$menus = array();

				if($rows2){
					$result2 = $query2->getResult();
		
					for($j=0; $j<$rows2; $j++) {
						array_push($menus, array('text' => $result2[$j]->title, 'tag' => 'option|'.$result2[$j]->id));
					}
				}

				array_push($menus, array('text' => "옵션추가", 'tag' => 'option-add|'.$result[$i]->id, 'icon' => 'bx bx-edit-alt', 'backColor' => '#bebebe', 'color' => '#fafafa'));

				array_push($arrList, array('text' => $result[$i]->title, 'tag' => 'optiongroup|'.$result[$i]->id, 'nodes' => $menus));
			}
		}

		array_push($arrList, array('text' => "옵션그룹추가", 'tag' => 'optiongroup-add', 'icon' => 'bx bx-edit-alt', 'backColor' => '#a2a2a2', 'color' => '#fafafa'));

		$returnVal["list"] = $arrList;

		return $returnVal;
	}

	public function postOptiongroup()
	{
    $builder = $this->db->table('optiongroup');

		$max_sort_query = $this->db->query("select ifnull(max(sort)+1, 1) as max_sort from optiongroup where shop='".$this->request->getPost('sid')."' and menu='".$this->request->getPost('mid')."'");
		$max_sort_result = $max_sort_query->getResult();
		$max_sort = $max_sort_result["0"]->max_sort;

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'menu' => $this->request->getPost('mid'), 
			'title' => $this->request->getPost('optiongroup_title'),
			'choice' => $this->request->getPost('optiongroup_choice'),
			'maxium' => $this->request->getPost('optiongroup_maxium'),
			'sort' => $max_sort, 
		];
		
    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|옵션그룹관리|등록되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}

	public function ajaxLoadOptiongroup() 
	{
		$sql = "select *, (select count(id) from option where optiongroup=optiongroup.id) as option_cnt, md5(id) as cogid from optiongroup where shop='".$this->request->getPost('sid')."' and id='".$this->request->getPost('ogid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();
		$result = null;

		$resultVal["status"] = "OK";

		if($rows) {
			$result = $query->getResult();
		}

		$resultVal["data"] = $result[0];

		return $resultVal;
	}

	public function putOptiongroup()
	{
    $builder = $this->db->table('optiongroup');

		$data = [
			'title' => $this->request->getPost('optiongroup_title'),
			'choice' => $this->request->getPost('optiongroup_choice'),
			'maxium' => $this->request->getPost('optiongroup_maxium'),
		];
		
		$builder->where('id', $this->request->getPost('ogid'));
		$builder->update($data);

		$this->session->setFlashdata('message', 'primary|옵션그룹관리|수정되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}

	public function delOptiongroup()
	{
    $builder = $this->db->table('optiongroup');

		$builder->where('id', $this->request->getPost('ogid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|옵션그룹관리|삭제되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}

	public function postOption()
	{
    $builder = $this->db->table('option');

		$max_sort_query = $this->db->query("select ifnull(max(sort)+1, 1) as max_sort from option where shop='".$this->request->getPost('sid')."' and menu='".$this->request->getPost('mid')."' and optiongroup='".$this->request->getPost('ogid')."'");
		$max_sort_result = $max_sort_query->getResult();
		$max_sort = $max_sort_result["0"]->max_sort;

		$data = [
			'shop' => $this->request->getPost('sid'), 
			'menu' => $this->request->getPost('mid'), 
			'optiongroup' => $this->request->getPost('ogid'), 
			'title' => $this->request->getPost('option_title'),
			'price' => $this->request->getPost('option_price'),
			'sort' => $max_sort, 
		];
		
    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|옵션관리|등록되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}

	public function ajaxLoadOption() 
	{
		$sql = "select *, (select id from optiongroup where optiongroup=optiongroup.id) as optiongroup_id, (select title from optiongroup where optiongroup=optiongroup.id) as optiongroup_title, md5(id) as coid from option where shop='".$this->request->getPost('sid')."' and id='".$this->request->getPost('oid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();
		$result = null;

		$resultVal["status"] = "OK";

		if($rows) {
			$result = $query->getResult();
		}

		$resultVal["data"] = $result[0];

		return $resultVal;
	}

	public function putOption()
	{
    $builder = $this->db->table('option');

		$data = [
			'title' => $this->request->getPost('option_title'),
			'price' => $this->request->getPost('option_price'),
		];
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);

		$this->session->setFlashdata('message', 'primary|옵션관리|수정되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}

	public function delOption()
	{
    $builder = $this->db->table('option');

		$builder->where('id', $this->request->getPost('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|옵션관리|삭제되었습니다.');

		$returnVal["status"] = "OK";

		return $returnVal;
	}










	public function findShop()
	{
		$sql = "select id, title from shop where (id like '%".$this->request->getPost('searchshop')."%' OR title like '%".$this->request->getPost('searchshop')."%') order by title";

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

	public function getCategoryList()
	{
		$sql = "select category.*, shop.title as shop_title, (select count(id) from menu where category=category.id) as cnt_menu from category, shop where shop=shop.id order by shop, sort";

		$query = $this->db->query($sql);

    $rows = $query->getNumRows();
    $result = $query->getResult();

		$returnVal["list"] = $result;

		//print_r($menulist);exit;
 
		return $returnVal;
	}

	public function getCategoryData()
	{
		$returnVal = null;

		$sql = "select category.*, shop.title as shop_title from category, shop where shop=shop.id and category.id='".$this->request->getGet('cid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}

		return $returnVal;
	}

	public function getMenuList()
	{
		$sql = "select menu.*, category.title from menu, category where category=category.id order by menu.sort";

		$query = $this->db->query($sql);

    $rows = $query->getNumRows();
    $result = $query->getResult();

		$returnVal["list"] = $result;

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
}
?>