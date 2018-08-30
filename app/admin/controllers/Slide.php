<?php
/*
* Slide 轮播图
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

	public function index(){

		$where = "WHERE 1=1";

		$title = trim($this->input->get('title'));
		if(!empty($title)){
			$where .= " AND title LIKE '%{$title}%'";
		}

	    $config = array();
	    $config['per_page'] = 10; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    $query = $this->db->query("SELECT * FROM slide {$where}");
	    $result['total'] = $query->num_rows();

	    $query = $this->db->query("SELECT * FROM slide {$where} ORDER BY id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();

	    $config['base_url'] = site_url('slide/index');//'admin.php/slide/index?';
	    
		$config['first_link'] = '首页';
		$config['first_tag_open'] = '<span class="first paginate_button paginate_button_disabled">';
		$config['first_tag_close'] = '</span>';

		$config['last_link'] = '尾页';
		//$config['last_tag_open'] = '<div>';
		//$config['last_tag_close'] = '</div>';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '<span class="next paginate_button">';
		$config['next_tag_close'] = '</span>';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '<span class="previous paginate_button paginate_button_disabled">';
		$config['prev_tag_close'] = '</span>';

		$config['cur_tag_open'] = ' <b>';
		$config['cur_tag_close'] = '</b>';

	    $config['total_rows'] = $result['total'];//总条数
	    $config['num_links']  = 2;//页码连接数
	    $config['use_page_numbers'] = TRUE;//使用页码而不是offset
	    $config['use_page_titles']  = TRUE;
	    $config['page_query_string'] = TRUE;
	    $this->load->library('pagination');//加载ci pagination类
	    $this->pagination->initialize($config);

	    $data = array(
	    	'title' => $title,
	        'rows' => $result['list'],
	        'total'  => $result['total'],
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links(),
	    );

		$this->load->view('slide/index.html',$data);
	}
	
	public function add(){
		$this->load->view('slide/add.html');
	}

	public function edit($id=null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$data = array();
		$query = $this->db->query("SELECT * FROM slide WHERE id={$id}");
	    $data = $query->row();
		$this->load->view('slide/edit.html',$data);

	}

	public function save(){

		$id = $this->input->post('id');

		$ord = $this->input->post('ord');

		$title = $this->input->post('title');

		$content = $this->input->post('content');

		$data = array(
		    'title' => $title,
		    'content' => $content,
		    'ord' => $ord,
		    'createTime' => date('Y-m-d H:i:s')
		);

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
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$this->upload->do_upload('attchment');
			$info = $this->upload->data();

		    if($info && !empty($id)){//删除旧图
				$query = $this->db->query("SELECT pic FROM slide WHERE id={$id}");
	    		$row = $query->row(); 
				@unlink($row->pic);
		    }

		    $data['pic'] = $config['upload_path'].$name;

		}

		if(empty($id)){
			$query = $this->db->query("SELECT * FROM slide WHERE title='{$title}'");
	    	$row = $query->row(); 
			if($row){
				jump('该信息已存在',site_url('slide/add'));
				exit();
			}
			
			$bool = $this->db->insert('slide', $data);

		}else{
			$this->db->where('id', $id);
			$bool = $this->db->update('slide', $data);
		}

		if($bool) {
            jump('操作成功',site_url('slide/index'));
        }else{
            jump('操作失败');
        }

	}

	public function del($id = null){

		$pid = $this->input->post('id');

		$gid = $id;

		$bool = false;

		if($pid){//多删

			$query = $this->db->query("SELECT * FROM slide WHERE id IN(".implode(',', $pid).")");
	    	$rows = $query->result();

			foreach ($rows as $k => $v) {
				@unlink($v->articleAttach);
			}

			$bool = $this->db->query("DELETE FROM slide WHERE id IN(".implode(',', $pid).")"); 

		}else if (!empty($gid)) {//单删

			//需要解散组？

			$query = $this->db->query("SELECT * FROM slide WHERE id={$gid}");
	    	$row = $query->row();
			//删除图片
			@unlink($row->articleAttach);

			$bool = $this->db->delete('slide', array('id' => $gid));

		}
		
		if($bool) {
            jump('操作成功',site_url('slide/index'));
        }else{
            jump('操作失败');
        }

	}

}
?>