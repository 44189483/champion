<?php
/*
* Proveseach 证明查询
* @package	Proveseach
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class Proveseach extends CI_Controller{

	public function __construct(){
		
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();

		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

		$this->header['cmenu'] = __CLASS__;

	}

	public function Index(){

	    $this->load->view('templates/header.html');
		$this->load->view('./chaxun1.html');
		$this->load->view('templates/footer.html',$this->header);

	}

	//查询结果
	public function result(){

		$keywords = $this->input->post('keywords');

		//详情
		$query = $this->db->query("SELECT * FROM activity WHERE serviceNumber='{$keywords}'");
    	$res = $query->row();

    	if(!$res){
    		$this->load->view('./noresult.html');
    	}else{
    		$this->load->view('templates/header.html');
    		$this->load->view('./chaxun2.html',$res);
    		$this->load->view('templates/footer.html',$this->header);
    	}
		
	}

}
?>