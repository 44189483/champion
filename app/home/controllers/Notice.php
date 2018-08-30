<?php
/*
* Notice 通知
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
		$this->load->database();
		$this->load->helper('func_helper');

		//帐号为空跳到登录
		// if(empty($this->session->member->id) || $this->session->member->status == 0){
		// 	redirect(site_url('/member/login'));
		// }

		$this->header['cmenu'] = __CLASS__;

		$this->mid = $this->session->member ? $this->session->member->id : 1;

	}

	public function index(){

		$file = $this->input->get('file');

		$sql = "SELECT id,title,createTime FROM article WHERE isShow=1 AND (type=0 OR type=4)"; 

		//ajax生成列表
		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数

			//获取当前分页页码数
		    $current_page = intval($this->input->get('page')); 
		    //设置偏移量 限定
		    $offset = ($current_page - 1) * $per_page; 

		    $sql .= " LIMIT {$offset},{$per_page}";	

		    $query = $this->db->query($sql);
		    $notice = $query->result();
		    foreach ($notice as $key => $val) {

		    	$notice[$key]->title = utfSubstr($val->title,0,30);

		    	//查询不存在就是没有看过的公告
		    	$que = $this->db->query("SELECT * FROM notice_read WHERE mid={$this->mid} AND nid={$val->id}");
		    	$row = $que->row();
		    	$notice[$key]->read = $row == true ? 1 : 0;

		    }

		    if($notice){
		    	echo $json = json_encode($notice);
		    }

		}else{

		    //公告信息
		    $query = $this->db->query($sql);
		    $data['total'] = $query->num_rows();

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('notice/list.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//搜索结果页 公告信息
	public function search(){

		//查询内容
		$keyword = $data['keyword'] = $this->input->get('keyword');

		$file = $this->input->get('file');

		$per_page = 5; //每页显示的数据数

		//获取当前分页页码数
	    $current_page = intval($this->input->get('page')); 
	    //设置偏移量 限定
	    $offset = ($current_page - 1) * $per_page;

	    $sql = "SELECT id,title,createTime FROM article WHERE isShow=1 AND title LIKE '%{$keyword}%' AND (type=0 OR type=4) ORDER BY id DESC";

	    $query = $this->db->query($sql);
	    $data['total'] = $query->num_rows();

		if($file == 'ajax'){
			$sql .= " LIMIT {$offset},{$per_page}";
		}

		//公告信息
	    $query = $this->db->query($sql);
	    $data['notice'] = $query->result();
	    foreach ($data['notice'] as $key => $val) {

	    	$data['notice'][$key]->title = utfSubstr($val->title,0,50);
	    	
	    	//查询不存在就是没有看过的公告
	    	$que = $this->db->query("SELECT * FROM notice_read WHERE mid={$this->mid} AND nid={$val->id}");
	    	$row = $que->row();
	    	$data['notice'][$key]->read = $row == true ? 1 : 0;

	    }

	    if($file == 'ajax'){

	    	if($data['notice']){
		    	echo $json = json_encode($data['notice']);
		    }

	    }else{
	    	$this->load->view('templates/header.html',$this->header);
			$this->load->view('notice/search.html',$data);
			$this->load->view('templates/footer.html');
	    }

	}

	public function detail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT id,type,title,content,createTime FROM article WHERE id={$id}");
    	$res = $query->row();

    	//问卷调查
    	if($res->type == 4){
    		
    		$res->content = unserialize($res->content);

    		//查询是否曾提交过
    		$que = $this->db->query("SELECT * FROM answer WHERE qid={$res->id} AND mid={$this->mid}");
    		$res->isSave = $que->num_rows() > 0 ? 1 : 0;

    	}

    	//公告信息 已查看记录
		$que = $this->db->query("SELECT * FROM notice_read WHERE nid={$id} AND mid={$this->mid}");
		$row = $que->row();

		if(!$row){

			$data = array(
				'mid' => $this->mid,
			    'nid' => $id
			);
			$this->db->insert('notice_read', $data);

		}

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('notice/detail.html',$res);
		
	}

	//提交调查问卷
	public function examine(){

		$qid = $this->input->post('id');

		//获取问卷标题
		$query = $this->db->query("SELECT title FROM article WHERE type=4 AND id={$qid}");
	    $res = $query->row();

		//序列化 题
		$reply = serialize($this->input->post());

		$data = array(
			'qid' => $qid,
		    'mid' => $this->mid,
		    'reply' => $reply,
		    'createTime' => date('Y-m-d H:i:s')
		);

		$bool = $this->db->insert('answer', $data);

		if($bool){

			//会员积分查询是否已参与
	    	$que = $this->db->query("SELECT * FROM users_integral WHERE type=4 AND cid={$qid} AND mid={$this->mid}");
	    	$hadIntergral = $que->num_rows();

	    	if($hadIntergral == 0){

				$integral = 15;

				$content = "参与问卷调查 ".$res->title." 获得{$integral}积分";
	    		$status = 1;
	    		$this->session->member->integral += $integral;

	    		//更新会员积分
				$this->db->query("UPDATE users SET integral=integral+{$integral} WHERE id={$this->mid}"); 

				$data = array(
	    			'type' => 4,
				    'cid' => $qid,
				    'mid' => $this->mid,
				    'content' => $content,
				    'integral' => $integral,
				    'status' => $status,
				    'createTime' => date('Y-m-d H:i:s')
				);

				$this->db->insert('users_integral', $data);

			}

			echo '提交成功';
			
		}else{
			echo '提交失败';
		}

	}

	//小程序
	public function noticedetail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT id,type,title,content,createTime FROM article WHERE id={$id}");
    	$res = $query->row();

    	//问卷调查
    	if($res->type == 4){
    		
    		$res->content = unserialize($res->content);

    		//查询是否曾提交过
    		$que = $this->db->query("SELECT * FROM answer WHERE qid={$res->id} AND mid={$this->mid}");
    		$res->isSave = $que->num_rows() > 0 ? 1 : 0;

    	}

    	//公告信息 已查看记录
		$que = $this->db->query("SELECT * FROM notice_read WHERE nid={$id} AND mid={$this->mid}");
		$row = $que->row();

		if(!$row){

			$data = array(
				'mid' => $this->mid,
			    'nid' => $id
			);
			$this->db->insert('notice_read', $data);

		}

    	echo json_encode($res);
		
	}

	//提交调查问卷
	public function examine1(){

		$json = file_get_contents("php://input");

		$array = json_decode(htmlspecialchars_decode($json), true);

		$qid = $array['id'];

		//获取问卷标题
		$query = $this->db->query("SELECT title FROM article WHERE type=4 AND id={$qid}");
	    $res = $query->row();

		//序列化 题
		$reply = serialize($array);

		$data = array(
			'qid' => $qid,
		    'mid' => $this->mid,
		    'reply' => $reply,
		    'createTime' => date('Y-m-d H:i:s')
		);

		$bool = $this->db->insert('answer', $data);

		if($bool){

			//会员积分查询是否已参与
	    	$que = $this->db->query("SELECT * FROM users_integral WHERE type=4 AND cid={$qid} AND mid={$this->mid}");
	    	$hadIntergral = $que->num_rows();

	    	/*

	    	if($hadIntergral == 0){

				$integral = 15;

				$content = "参与问卷调查 ".$res->title." 获得{$integral}积分";
	    		$status = 1;
	    		$this->session->member->integral += $integral;

	    		//更新会员积分
				$this->db->query("UPDATE users SET integral=integral+{$integral} WHERE id={$this->mid}"); 

				$data = array(
	    			'type' => 4,
				    'cid' => $qid,
				    'mid' => $this->mid,
				    'content' => $content,
				    'integral' => $integral,
				    'status' => $status,
				    'createTime' => date('Y-m-d H:i:s')
				);

				$this->db->insert('users_integral', $data);

			}

			*/

			echo '提交成功';
			
		}else{
			echo '提交失败';
		}

	}

}
?>