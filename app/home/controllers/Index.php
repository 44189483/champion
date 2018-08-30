<?php
/*
* Index 首页
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
		$this->load->database();
		$this->load->helper('func_helper');

		//帐号为空跳到登录
		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

		$this->header['cmenu'] = __CLASS__;
	}

	public function index(){

		$keyword = $this->input->get('keyword');

		$file = $this->input->get('file');

		//首页无刷新
		if($file == 'ajax'){

			//轮播图
			$b_query = $this->db->query("SELECT id,pic FROM slide ORDER BY id DESC LIMIT 5");
		    $result['banner'] = $b_query->result();	    

		    //公告信息
		    $result['no_read_notice'] = 0;
		    $n_query = $this->db->query("SELECT id,title FROM article WHERE type=0 ORDER BY id DESC");
		    $result['notice'] = $n_query->result();
		    foreach ($result['notice'] as $key => $val) {
		    	//查询不存在就是没有看过的公告
		    	$query = $this->db->query("SELECT * FROM notice_read WHERE mid={$this->session->member->id} AND nid={$val->id}");
		    	$row = $query->row();
		    	if(!$row){
		    		$result['no_read_notice'] += 1;
		    	}
		    }

		    //志愿服务
		    $zy_query = $this->db->query("SELECT id,title,pic FROM activity ORDER BY endTime DESC LIMIT 1");
		    $result['volunteer'] = $zy_query->row();

		    //我们的故事
		    $gs_query = $this->db->query("SELECT id,title,attach,SUBSTRING(createTime,1,10) AS date,mid FROM article WHERE type=1 ORDER BY id DESC LIMIT 1");
		    $result['story'] = $gs_query->row();

		    if($result['story']){
			    //发布者
			    $zz_query = $this->db->query("SELECT realname FROM users WHERE id={$result['story']->mid} LIMIT 1");
			    $res = $zz_query->row();
			    $result['story']->author = !empty($res->realname) ? $res->realname : '';
			}

		    //教程
		    $course_query = $this->db->query("SELECT * FROM course WHERE status=1 AND type=1 ORDER BY id DESC LIMIT 1");
		    $result['course'] = $course_query->row();

		    $result['course']->content = utfSubstr(strip_tags($result['course']->content),0,45);

		    echo $json = json_encode($result);

		}else{

			//公告信息
		    $result['no_read_notice'] = 0;
		    $n_query = $this->db->query("SELECT id,title FROM article WHERE type=0 ORDER BY id DESC");
		    $result['notice'] = $n_query->result();
		    foreach ($result['notice'] as $key => $val) {
		    	//查询不存在就是没有看过的公告
		    	$query = $this->db->query("SELECT * FROM notice_read WHERE mid={$this->session->member->id} AND nid={$val->id}");
		    	$row = $query->row();
		    	if(!$row){
		    		$result['no_read_notice'] += 1;
		    	}
		    }

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('index.html',$result);
			$this->load->view('templates/footer.html');

		}

	}

	//查询
	public function seach(){

		//查询内容
		$keyword = $this->input->get('keyword');

		//媒体资讯
		$zx_query = $this->db->query("SELECT * FROM article WHERE title like '%{$keyword}%' AND type=1 ORDER BY id DESC");
	    $data['zx_total'] = $zx_query->num_rows();	

		//活动资料
		$hd_query = $this->db->query("SELECT * FROM activity WHERE title like '%{$keyword}%' AND status=1	ORDER BY id DESC");
		$data['hd_total'] = $hd_query->num_rows();

		//通知公告
		$gg_query = $this->db->query("SELECT * FROM article WHERE title like '%{$keyword}%' AND (type=0 OR type=4) ORDER BY id DESC");
	    $data['gg_total'] = $gg_query->num_rows();

	    //在线教程
		$course_query = $this->db->query("SELECT * FROM course WHERE status=1 AND title like '%{$keyword}%' ORDER BY id DESC");
	    $data['course_total'] = $course_query->num_rows();

	    $data['total'] = $data['zx_total'] + $data['hd_total'] + $data['gg_total'] + $data['course_total'];

	    $data['keyword'] = $keyword;

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('seach.html',$data);
		$this->load->view('templates/footer.html');

	}

}
?>