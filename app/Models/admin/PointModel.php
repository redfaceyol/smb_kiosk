<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class PointModel extends Model
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

	public function getPointList()
	{
		$page_per_block = 10;
		$num_per_page = 10;

		$sql = "select point.*, shop.title as shop_title from point, shop where shop=shop.id ";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (shop.title like '%".$this->request->getGet('searchtext')."%' or telnumber like '%".$this->request->getGet('searchtext')."%') ";
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

			$this->response->redirect("/admin/point/pointList?".$_Link);

			exit;
		}

		if( $page > $total_page ){
			$page = $total_page;
		}

		$total_count = $rows;

		$sql = "select * from ( ".$sql." ) A order by id desc";

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

	public function delPoint()
	{
    $builder = $this->db->table('point');

		$builder->where('id', $this->request->getGet('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|포인트관리|삭제되었습니다.');

		$this->response->redirect("/admin/point/pointList?page=".$this->request->getGet('page'));
	}
}
?>