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

			$sql = "select shop.*, (select count(id) from kiosk where shop=shop.id ) as kioskcnt, (SELECT sum(amount) FROM payment_".date("Y")." where shop=shop.id and DATE_FORMAT(payment_datetime, '%Y%m%d')='".date("Ymd")."' and (van='KSNET' and authnumber!='1000')) as totalsales from shop where 1=1";

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

	public function getSalesDashboard()
	{
		$sql = "select sum(amount) amount, payment_day from ( SELECT amount, DATE_FORMAT(payment_datetime, '%Y%m%d') as payment_date, DATE_FORMAT(payment_datetime, '%d') as payment_day FROM payment_".date("Y")." where shop='".$this->request->getGet('sid')."' and (van='KSNET' and authnumber!='1000')) A where payment_date>=SUBDATE(now(), 7) group by payment_date";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		$returnVal["dailysalelist"] = $result;
 
		return $returnVal;
	}

	public function getStartYear()
	{
		$sql = "select year(registe_datetime) as startyear from shop where id='".$this->request->getGet('sid')."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		$returnVal = $result[0]->startyear;

		return $returnVal;
	}

	public function getYearSales($getYear)
	{
		$sql = "select sum(amount) amount, payment_month from ( SELECT amount, DATE_FORMAT(payment_datetime, '%Y%m%d') as payment_date, month(payment_datetime) as payment_month FROM payment_".$getYear." where shop='".$this->request->getGet('sid')."' and (van='KSNET' and authnumber!='1000')) A group by payment_month";
    $query = $this->db->query($sql);
    $result = $query->getResult();

		for($i=1; $i<=12; $i++) {
			$returnVal[$i] = 0;
		}

		foreach($result as $row) {
      $returnVal[$row->payment_month] = $row->amount;
    }

		return $returnVal;
	}

	public function getMonthSales($getYear, $getMonth)
	{
		$sql = "select sum(amount) amount, payment_day from ( SELECT amount, DATE_FORMAT(payment_datetime, '%Y%m%d') as payment_date, day(payment_datetime) as payment_day FROM payment_".$getYear." where shop='".$this->request->getGet('sid')."' and month(payment_datetime)='".$getMonth."' and (van='KSNET' and authnumber!='1000')) A group by payment_day";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		for($i=1; $i<=date("t", strtotime($getYear."-".$getMonth."-1")); $i++) {
      $returnVal[$i] = 0;
    }

		foreach($result as $row) {
      $returnVal[$row->payment_day] = $row->amount;
    }

		return $returnVal;
	}

	public function getDaySales($getYear, $getMonth, $getDay)
	{
		$sql = "select sum(amount) amount, payment_hour from ( SELECT amount, DATE_FORMAT(payment_datetime, '%Y%m%d') as payment_date, hour(payment_datetime) as payment_hour FROM payment_".$getYear." where shop='".$this->request->getGet('sid')."' and month(payment_datetime)='".$getMonth."' and day(payment_datetime)='".$getDay."' and (van='KSNET' and authnumber!='1000')) A group by payment_hour";
    $query = $this->db->query($sql);
    $result = $query->getResult();

    for($i=0; $i<=23; $i++) {
      $returnVal[$i] = 0;
    }

    foreach($result as $row) {
      $returnVal[$row->payment_hour] = $row->amount;
    }

		return $returnVal;
	}

	public function getSalesDetail($getStartDate, $getEndDate)
	{
		$page_per_block = 10;
		$num_per_page = 20;

		if(substr($getStartDate, 0, 4) == substr($getEndDate, 0, 4)) {
			$sql = "select A.*, kiosk.number FROM (select *, DATE_FORMAT(payment_datetime, '%Y-%m-%d') as payment_date from payment_".substr($getStartDate, 0, 4).") A, kiosk where A.shop='".$this->request->getGet('sid')."' and (van='KSNET' and authnumber!='1000') and payment_date between '".$getStartDate."' and '".$getEndDate."' and A.kiosk=kiosk.id";
		}
		else {
			$sql = "( select A.*, kiosk.number FROM (select *, DATE_FORMAT(payment_datetime, '%Y-%m-%d') as payment_date from payment_".substr($getStartDate, 0, 4).") A, kiosk where A.shop='".$this->request->getGet('sid')."' and (van='KSNET' and authnumber!='1000') and payment_date between '".$getStartDate."' and '".$getEndDate."' and A.kiosk=kiosk.id ) union ( select A.*, kiosk.number FROM (select *, DATE_FORMAT(payment_datetime, '%Y-%m-%d') as payment_date from payment_".substr($getEndDate, 0, 4).") A, kiosk where A.shop='".$this->request->getGet('sid')."' and (van='KSNET' and authnumber!='1000') and payment_date between '".$getStartDate."' and '".$getEndDate."' and A.kiosk=kiosk.id )";
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

				$this->response->redirect("/admin/sales/salesDetail?".$_Link);

				exit;
		}

		if( $page > $total_page ){
				$page = $total_page;
		}

		$total_count = $rows;

		$sql = "select * from ( ".$sql." ) A order by payment_datetime asc";

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
}
?>