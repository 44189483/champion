<?php
/*
* Option 设置
* @package	Option
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Option extends CI_Controller{

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

    	//网站信息
		$query = $this->db->query("SELECT * FROM baseoption WHERE optionType='index_amount'");

		foreach ($query->result() as $v){
		    $data[$v->optionKey] = $v->optionValue;
		}

		if($this->input->post()){

			$query = $this->db->query("DELETE FROM baseoption WHERE optionType='index_amount'");

			foreach($this->input->post() as $k => $v){
				$data = array(
				    'optionType' => 'index_amount',
				    'optionKey' => $k,
				    'optionValue' => $v
				);
				$this->db->insert('baseoption', $data);

			}

			redirect(site_url('option'));
			
		}

		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);
		$this->load->view('option.html',$data);

	}

}
?>