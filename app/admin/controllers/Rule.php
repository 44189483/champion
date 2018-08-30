<?php
/*
* Rule 发稿规则
* @package	Rule
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Rule extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();

		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}

		$this->head_data['cname'] = __CLASS__;
		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);

	}

	public function index(){

		$query = $this->db->query("SELECT content FROM article WHERE type=5");
	    $data = $query->row();
		$this->load->view('rule.html',$data);

	}

	public function save(){

		$content = $this->input->post('content');

		$data = array(
		    'content' => $content
		);

		$this->db->where('type', 5);
		$bool = $this->db->update('article', $data);
		
		if($bool) {
            jump('操作成功',site_url('rule/index'));
        }else{
            jump('操作失败');
        }

	}

}
?>