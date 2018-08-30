<?php
/*
* Slide 幻灯片
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
		$this->load->database();

		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}

		$this->head_data['cname'] = __CLASS__;
		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);

	}

	public function index($id = null){

	    $query = $this->db->query("SELECT * FROM img WHERE type='banner' ORDER BY ord DESC,id DESC");
	    $rows = $query->result();

	    $data = array(
	        'rows' => $rows
	    );
		$this->load->view('slide.html',$data);
	}

	public function save(){

		$link = $this->input->post('link');

		$ord = $this->input->post('ord');

		//文件存在判断
		if(!empty($_FILES["attchment"]["name"]) && is_uploaded_file($_FILES["attchment"]["tmp_name"])){

			//重命名
			$attchname = $_FILES["attchment"]["name"];
			$ext = substr($attchname,strripos($attchname,'.') + 1);
			$name = date('Ymd').rand(0,999).'.'.$ext;
			$config['file_name'] = $name;

			$config['upload_path'] = 'upload/banner/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '0';
			$config['max_width'] = '640';
			$config['max_height'] = '300';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$upload = $this->upload->do_upload('attchment');

			if(!$upload){
		      $error = $this->upload->display_errors();
		      if($error = "The image you are attempting to upload doesn't fit into the allowed dimensions."){
		      	show_error('图片尺寸有误',null,'错误提示');	
		      	exit();
		      }
		    }

			$info = $this->upload->data();

		    if($info && !empty($id)){//删除旧图
				$query = $this->db->query("SELECT url FROM img WHERE id={$id}");
	    		$row = $query->row(); 
				@unlink($row->url);
		    }

		    $pic = $config['upload_path'].$name;

		}else{
			show_error('<a href="'.base_url().'slide/index">图片不能为空,点击返回!</a>',null,'错误提示');
		}
		
		$data = array(
		    'url' => $pic,
		    'link' => $link,
		    'type' => 'banner',
		    'ord' => $ord
		);
		
		$bool = $this->db->insert('img', $data);
        redirect(site_url('slide/index'));

	}

	public function del($id){

		$bool = false;

		$query = $this->db->query("SELECT * FROM img WHERE id={$id}");
    	$row = $query->row();
		//删除图片
		@unlink($row->url);

		$bool = $this->db->delete('img', array('id' => $id));

		
		if($bool) {
            jump('操作成功',site_url('slide/index'));
        }else{
            jump('操作失败');
        }

	}

}
?>