<?php
/*
* Notice 公告
* @package	Notice
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Notice extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}
		$this->load->library('pagination');
		$this->load->database();
		$this->head_data['cname'] = __CLASS__;
		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);

	}

	public function index(){

		$where = "WHERE type=0";

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

	    $query = $this->db->query("SELECT id,title,isShow,createTime FROM article {$where} ORDER BY id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();

	    $config['base_url']   = site_url("notice/index");//'admin.php/news/index?';
	    
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
	        'rows' => $result['list'],
	        'total'  => $result['total'],
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links(),
	    );
		$this->load->view('notice/index.html',$data);
	}

	public function save(){

		$id = $this->input->post('id');

		$title = $this->input->post('title');

		$show = $this->input->post('show') == null ? 0 : 1;

		$content = $this->input->post('content');

		$time = $this->input->post('time');

		$push = $this->input->post('push');
		
		$data = array(
		    'title' => $title,
		    'content' => $content,
		    'type' => 0,
		    'isShow' => $show,
		    'createTime' => $time
		);

		if(!empty($id)){
			$this->db->where('id', $id);
			$bool = $this->db->update('article', $data);
		}else{
			$bool = $this->db->insert('article', $data);
		}
		
		if($bool) {

			//推送
			if($push == 1){

				require APPPATH.'libraries/Jpush/config.php';

				// 简单推送示例
				//->addRegistrationId($registration_ids)
				
				//查询接收推送的设备
				$que = $this->db->query("SELECT registration_id FROM notice_push WHERE status=1");
	    		$res = $que->result();

	    		foreach ($res as $key => $val) {
	    			$registration_ids[] = $val->registration_id;
				}

				$push_payload = $client->push()
					    ->setPlatform('all')
					    ->addRegistrationId($registration_ids)
					    ->setNotificationAlert($title);
				try {
				    $response = $push_payload->send();
				    //print_r($response);
				} catch (\JPush\Exceptions\APIConnectionException $e) {
				    // try something here
				    print $e;
				} catch (\JPush\Exceptions\APIRequestException $e) {
				    // try something here
				    print $e;
				}

			}
			
            jump('操作成功',site_url("notice/index"));

        }else{
            jump('操作失败');
        }

	}

	public function add(){
		$this->load->view('notice/add.html');
	}

	public function edit($id=null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$data = array();
		$query = $this->db->query("SELECT * FROM article WHERE id={$id}");
	    $data = $query->row();
		$this->load->view('notice/edit.html',$data);

	}

	public function del($id = null){

		$pid = $this->input->post('id');

		$gid = $id;

		$bool = false;

		if($pid){//多删

			//同时删除会员查看记录
			$this->db->query("DELETE FROM notice_read WHERE nid IN(".implode(',', $pid).")"); 

			$bool = $this->db->query("DELETE FROM article WHERE id IN(".implode(',', $pid).")"); 

		}else if (!empty($gid)) {//单删

			//同时删除会员查看记录
			$this->db->delete('notice_read', array('nid' => $gid));

			$bool = $this->db->delete('article', array('id' => $gid));

		}
		
		if($bool) {
            jump('操作成功',site_url("notice/index"));
        }else{
            jump('操作失败');
        }

	}

}
?>