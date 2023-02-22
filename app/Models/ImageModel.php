<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\IncomingRequest;

class ImageModel extends Model
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

	public function getMenuImage($inId)
	{
		$sql = "select image from menu where id='".$inId."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		return $result[0]->image;
	}

	public function getMenuThumbImage($inId)
	{
		$sql = "select thumbimage from menu where id='".$inId."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		return $result[0]->thumbimage;
	}

	public function getSignImage($inId)
	{
		$sql = "select signimage from shop where id='".$inId."'";
		$query = $this->db->query($sql);
		$result = $query->getResult();

		return $result[0]->signimage;
	}
}
?>