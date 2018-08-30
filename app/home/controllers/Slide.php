<?php
/*
* Slide 轮播图详情页
* @package	Slide
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Slide extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->helper('func_helper');
		$this->load->database();

		$this->header['cmenu'] = __CLASS__;

	}

	//判断是否登录
	private function isMemberLogin(){

		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

	} 

	public function detail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT * FROM slide WHERE id={$id}");
    	$res = $query->row();

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('slide/detail.html',$res);
		
	}

}
?>