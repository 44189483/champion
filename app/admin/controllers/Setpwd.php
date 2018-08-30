<?php
/*
* Setpwd 密码设置
* @package	Setpwd
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Setpwd extends CI_Controller{

	public function __construct(){
		
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url_helper');
		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}
		$this->load->database();
		$this->head_data['cname'] = __CLASS__;

	}

    public function index(){

		$query = $this->db->query("SELECT * FROM baseoption WHERE optionType='AdminContrl'");
		$data = $query->row();

		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);
		$this->load->view('setpwd.html',$data);

	}

	public function save(){

		$pwd = password_hash($this->input->post('newpwd'), PASSWORD_BCRYPT);

		$this->db->where('optionType', 'AdminContrl');
		$bool = $this->db->update('baseoption', array('optionValue' => $pwd));

		if($bool) {
            jump('操作成功',site_url('setpwd/index'));
        }else{
            jump('操作失败');
        }

	}

}
?>