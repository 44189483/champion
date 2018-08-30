<?php
/*
* Comment 评论相关
* @package	About
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Comment extends CI_Controller{

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
		
	}

	//评论列表
	public function index(){

		$type = $this->uri->segment(3);
		if(empty($type)){
    		show_error('参数有误',null,'提示');
    	}

		$id = $this->uri->segment(4);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

    	switch ($type) {
    		case 'activity':
    			$data['menu'] = '公益服务';
    			$table = 'activity';
    			$v = 1;
    			break;
    		case 'story':
    			$data['menu'] = '媒体资讯';
    			$table = 'article';
    			$v = 2;
    			break;
    		case 'course':
    			$data['menu'] = '在线教程';
    			$table = 'course';
    			$v = 3;
    			break;
    		case 'slide':
    			$data['menu'] = '轮播新闻';
    			$table = 'slide';
    			$v = 4;
    			break;
    	}

    	$que = $this->db->query("SELECT title FROM {$table} WHERE id={$id}");
    	$row = $que->row();

    	$data['title'] = $row->title; 

    	//查询评论人员
    	$sql = "
    		SELECT
    			m.nickname, 
    			m.portraitUri,
    			m.level,
    			c.id,
    			c.content,
    			c.comment,
    			c.praise,
    			c.createTime 
    		FROM 
    			comment c
    		INNER JOIN
    			users m
    		ON
    			c.mid=m.id
    		WHERE 
    			c.type={$v} AND c.aid={$id}
    			ORDER BY c.id DESC
    	";
    	$query = $this->db->query($sql);
    	$data['comments'] = $query->result();
    	foreach ($data['comments'] as $key => $val) {

    		$sql = "
				SELECT 
					m.nickname, 
	    			m.portraitUri,
	    			m.level,
	    			r.id,
	    			r.content,
	    			r.createTime  
				FROM 
					review r
				INNER JOIN
		    		users m
		    	ON
		    		r.mid=m.id
				WHERE 
					cid={$val->id}
    		";

    		$que = $this->db->query($sql);
    		$data['comments'][$key]->review = $que->result();
    		
    	}

		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);
		$this->load->view('comment/list.html',$data);

	}

	//删除评论
	public function del(){

		$cid = $this->input->get('cid');

		$rid = $this->input->get('rid');

		if(!is_numeric($cid) && !is_numeric($rid)){
    		show_error('参数有误',null,'提示');
    	}

    	$sql = "
    		SELECT 
    			c.aid,
    			(CASE 
    				WHEN c.type=1 THEN 'activity' 
    				WHEN c.type=2 THEN 'story' 
    				WHEN c.type=3 THEN 'course' 
    				WHEN c.type=4 THEN 'slide' 
    			END) AS menu 
    		FROM 
    			comment c
    	";

    	//删除评论
    	if(!empty($cid)){

    		$sql .= " WHERE c.id={$cid}";

    		$table = 'comment';

    		$id = $cid;

    		//其下所有对评论的回复信息一并删除
    		$this->db->delete('review', array('cid' => $cid));

    	}

    	//回复评论
    	if(!empty($rid)){

    		$sql .= "
				INNER JOIN
					review r
				ON
					c.id=r.cid
				WHERE 
					r.id={$rid}
    		";

    		$table = 'review';

    		$id = $rid;

    	}

		$que = $this->db->query($sql);
    	$row = $que->row();

    	$bool = $this->db->delete($table, array('id' => $id));
		if($bool) {
            jump('操作成功',site_url("comment/index/{$row->menu}/{$row->aid}"));
        }else{
            jump('操作失败');
        }

	}

}
?>