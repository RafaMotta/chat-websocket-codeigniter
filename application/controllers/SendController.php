<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SendController extends CI_Controller
{

	public function index()
	{
		$data	 = array();
		$allmsgs = $this->db->select('*')
			->from('tbl_msg')
			->get()
			->result_array();

		$data['allMsgs'] = $allmsgs;
		$this->load->view('message', $data);
	}

	public function send()
	{
		$arr['msg']		 = $this->input->post('message');
		$arr['date']	 = date('Y-m-d');
		$arr['status']	 = 1;
		$this->db->insert('tbl_msg', $arr);
		$detail			 = $this->db->select('*')->from('tbl_msg')->where('id', $this->db->insert_id())->get()->row();
		$msgCount		 = $this->db->select('*')->from('tbl_msg')->get()->num_rows();
		$arr['message']	 = $detail->msg;
		$arr['date']	 = date('m-d-Y', strtotime($detail->date));
		$arr['msgcount'] = $msgCount;
		$arr['success']	 = true;
		echo json_encode($arr);
	}
}