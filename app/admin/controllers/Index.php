<?php
/*
* Index 登录/验证码/首页
* @package	Index
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Index extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->head_data['cname'] = __CLASS__;
	}

	public function login(){

		$this->load->model('login_model');

		if($this->input->post('act') == 'submit'){
			
			if($this->input->post('provenum') != $this->session->authImg){
    			show_error('对不起，请输入正确的验证码！');
			}

			$row = $this->login_model->get_login();
				
			if(!$row){
				show_error('请您输入正确的用户名和密码！');
			}else{
				$this->session->admin = $this->input->post('username');
				jump('登陆成功',site_url('index'));
			}

		}else{
			$this->load->view('login.html');
		}


	}

	public function index(){
		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}
		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);
		$this->load->view('index.html');
	}

	public function provenum(){

    	$rand = "";

		for($i = 0;$i < 4;$i++){
			$rand.= dechex(rand(1,15));
		} 
		$this->session->authImg = $rand;

		$im = imagecreatetruecolor(110,45);//尺寸 

		$bg = imagecolorallocate($im,255,255,255);	//背景色

		imagefill($im,0,0,$bg);

		$te = imagecolorallocate($im,0,0,0);			//字符串颜色

		$te2 = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));

		for($i = 0; $i < 3; $i++){
			imageline($im,rand(0,110),0,110,40,$te2);
		}

		for($i = 0;$i < 200;$i++){
		    imagesetpixel($im,rand(0,110),rand(0,40),$te2);
		}

		imagestring($im,10,25,6,$rand,$te);//输出图像的位置（数字验证）

		header('Content-type:image/jpeg');

		imagejpeg($im);

		imagedestroy($im);

    }

    public function logout(){
    	//退出 清除session
		$this->session->admin = '';
		session_destroy();
		jump('退出成功',site_url('index/login'));
    }

}
?>