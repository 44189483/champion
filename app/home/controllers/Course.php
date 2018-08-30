<?php
/*
* Course 通知
* @package	Notice
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Course extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();
		$this->load->helper('func_helper');

		//帐号为空跳到登录
		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

		$this->header['cmenu'] = __CLASS__;

	}

	public function index(){

		$file = $this->input->get('file');

		$sql = "SELECT * FROM course";

		if($file == 'ajax'){
			
			$per_page = 5; //每页显示的数据数

			//获取当前分页页码数
		    $current_page = intval($this->input->get('page')); 
		    //设置偏移量 限定
		    $offset = ($current_page - 1) * $per_page;

		    $sql .= " LIMIT {$offset},{$per_page}"; 	

		    $query = $this->db->query($sql);
		    $list = $query->result();
		    foreach ($list as $key => $val) {
		    	$list[$key]->content = utfSubstr(strip_tags($val->content), 0, 50);
		    }
		    
		    if($list){
		    	echo $json = json_encode($list);
		    }

		}else{

		    $query = $this->db->query($sql);
		    $data['total'] = $query->num_rows();

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('course/list.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//搜索结果页
	public function search(){

		//查询内容
		$keyword = $result['keyword'] = $this->input->get('keyword');

		$file = $this->input->get('file');

		$per_page = 5; //每页显示的数据数

		//获取当前分页页码数
	    $current_page = intval($this->input->get('page')); 
	    //设置偏移量 限定
	    $offset = ($current_page - 1) * $per_page;

	    $sql = "SELECT * FROM course WHERE title LIKE '%{$keyword}%' ORDER BY id DESC";

		//统计所有总数
		$query = $this->db->query($sql);
	    $result['total'] = $query->num_rows();

		if($file == 'ajax'){
			$sql .= " LIMIT {$offset},{$per_page}";
		}

		$query = $this->db->query($sql);
		$result['list'] = $query->result();
	    foreach ($result['list'] as $key => $val) {
	    	$result['list'][$key]->content = utfSubstr($val->content, 0, 50);
	    }

	    if($file == 'ajax'){

	    	if($result['list']){
		    	echo $json = json_encode($result['list']);
		    }

	    }else{
	    	$this->load->view('templates/header.html',$this->header);
			$this->load->view('course/search.html',$result);
			$this->load->view('templates/footer.html');
	    }

	}

	public function detail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT * FROM course WHERE id={$id}");
    	$res = $query->row();

    	//会员积分查询是否已参与
    	$que = $this->db->query("SELECT * FROM users_integral WHERE type=3 AND cid={$id} AND mid={$this->session->member->id}");
    	$res->hadIntergral = $que->num_rows();

    	//会员没有参与 插入记录
    	if($res->hadIntergral == 0){

    		$content = '观看'.$res->title;

    		//if($res->type == 1){
    			$content .= '获得'.$res->integral.'积分';
    			$status = 1;
    			$where = "integral=integral+{$res->integral}";
    			$this->session->member->integral += $res->integral;

    			//更新会员积分
				$this->db->query("UPDATE {$this->db->dbprefix('users')} SET {$where} WHERE id={$this->session->member->id}"); 

				$data = array(
	    			'type' => 3,
				    'cid' => $id,
				    'mid' => $this->session->member->id,
				    'content' => $content,
				    'integral' => $res->integral,
				    'status' => $status,
				    'createTime' => date('Y-m-d H:i:s')
				);

				$this->db->insert('users_integral', $data);

    		//}

    		// if($res->type == 2){
    		// 	$content .= '使用'.$res->integral.'积分';
    		// 	$status = 2;
    		// 	$where = "integral=integral-{$res->integral}";
    		// 	$this->session->member->integral -= $res->integral; 
    		// }

    	}

    	//标记是否从搜索页面进来 好返回用
    	$res->sign = $this->uri->segment(4);

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('course/detail.html',$res);
		
	}

}
?>