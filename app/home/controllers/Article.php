<?php
/*
* Article 文章相关
* @package	Article
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Article extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();
		$this->load->helper('func_helper');

		//帐号为空跳到登录
		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

		$this->header['cmenu'] = __CLASS__;

	}

	//关于冠军惠
	public function about(){

		$query = $this->db->query("SELECT title,content FROM article WHERE type=6");
    	$res = $query->row();

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('article/detail.html',$res);
		
	}

}
?>