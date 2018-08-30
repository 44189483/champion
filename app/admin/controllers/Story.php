<?php
/*
* Story 媒体资讯
* @package	Story
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Story extends CI_Controller{

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

		$where = "WHERE type IN(1,2)";

		$search = '';
		
		$title = trim($this->input->get('title'));
		if(!empty($title)){
			$where .= " AND title LIKE '%{$title}%'";
			$search .= "&title={$title}";
		}

	    $config = array();
	    $config['per_page'] = 10; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    $query = $this->db->query("SELECT * FROM article {$where}");
	    $result['total'] = $query->num_rows();

	    $query = $this->db->query("SELECT * FROM article {$where} ORDER BY id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();
	    foreach ($result['list'] as $key => $val) {
	    	//查询会员姓名
	    	$que = $this->db->query("SELECT realname FROM users WHERE id={$val->mid}");
	    	$res = $que->row();
	    	if($res){
	    		$result['list'][$key]->realname = $res->realname;
	    	}else{
	    		$result['list'][$key]->realname = '官方';
	    	}
	    }

	    $config['base_url']   = site_url('story/index?').$search;//'admin.php/story/index?';

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

		$this->load->view('story/index.html',$data);
	}
	
	public function add(){

		$data['users'] = $this->getUsers();
		$this->load->view('story/add.html',$data);

	}

	public function edit($id=null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$data = array();
		$query = $this->db->query("SELECT * FROM article WHERE id={$id}");
	    $data = $query->row();
	    $data->users = $this->getUsers();
		$this->load->view('story/edit.html',$data);

	}

	public function save(){

		$id = $this->input->post('id');

		$mid = $this->input->post('mid');

		$title = $this->input->post('title');

		$type = $this->input->post('radio');

		if($type == 1){
			$content = $this->input->post('content');
		}else{
			$content = $this->input->post('link');
		}

		$datetime = $this->input->post('datetime');

		$data = array(
	    	'mid' => $mid,
	    	'isShow' => 1,
	    	'type' => $type,
	    	'title' => $title,
	    	'content' => $content,
		    'createTime' => $datetime
		);

		//文件存在判断
		if(!empty($_FILES["attchment"]["name"]) && is_uploaded_file($_FILES["attchment"]["tmp_name"])){

			//重命名
			$attchname = $_FILES["attchment"]["name"];
			$ext = substr($attchname,strripos($attchname,'.') + 1);
			$name = date('Ymd').rand(0,999).'.'.$ext;
			$config['file_name'] = $name;
			$config['upload_path'] = 'upload/article/pic/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '0';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$this->upload->do_upload('attchment');
			$info = $this->upload->data();

		    if($info && !empty($id)){//删除旧图
				$query = $this->db->query("SELECT attach FROM article WHERE id={$id}");
	    		$row = $query->row(); 
				@unlink($row->attach);
		    }

		    $data['attach'] = $config['upload_path'].$name;

		}

		if(empty($id)){
			$query = $this->db->query("SELECT * FROM article WHERE title='{$title}'");
	    	$row = $query->row(); 
			if($row){
				jump('该信息已存在',site_url('story/add'));
				exit();
			}
			
			$bool = $this->db->insert('article', $data);

		}else{
			$this->db->where('id', $id);
			$bool = $this->db->update('article', $data);
		}

		if($bool) {
            jump('操作成功',site_url('story/index'));
        }else{
            jump('操作失败');
        }

	}

	public function del($id = null){

		$pid = $this->input->post('id');

		$gid = $id;

		$bool = false;

		if($pid){//多删

			$query = $this->db->query("SELECT * FROM article WHERE id IN(".implode(',', $pid).")");
	    	$rows = $query->result();

			foreach ($rows as $k => $v) {
				@unlink($v->attach);
			}

			$bool = $this->db->query("DELETE FROM article WHERE id IN(".implode(',', $pid).")"); 

		}else if (!empty($gid)) {//单删

			$query = $this->db->query("SELECT * FROM article WHERE id={$gid}");
	    	$row = $query->row();
			//删除图片
			@unlink($row->attach);

			$bool = $this->db->delete('article', array('id' => $gid));

		}
		
		if($bool) {
            jump('操作成功',site_url('story/index'));
        }else{
            jump('操作失败');
        }

	}

	/*
	 * getUsers 获取媒体成员
	 * @return array
	 */
	public function getUsers(){
		$query = $this->db->query("SELECT id,realname FROM users WHERE level=2");
	    return $query->result_array();
	}

}
?>