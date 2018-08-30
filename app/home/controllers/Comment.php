<?php 
/*
* Comment 评论
* @package	Comment.php
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class Comment extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
	}

	//评论列表
	public function getdata(){

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
    			$v = 1;
    			break;
    		case 'story':
    			$v = 2;
    			break;
    		case 'course':
    			$v = 3;
    			break;
    		case 'slide':
    			$v = 4;
    			break;
    	}

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

    		//判断当前会员是否点过赞
    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=1 AND cid={$val->id} AND mid={$this->session->member->id}");
    		$zan_num = $zan_query->num_rows();

    		$data['comments'][$key]->isPraise = $zan_num > 0 ? 1 : 0;
    		
    	}
    	$data['id'] = $id;

    	$this->load->view('comment/list.html',$data);

	}

	//点赞评论
	public function praise(){

		if($this->session->member->id > 0){

	    	//评论ID
	    	$id = $this->input->post('id');

	    	if(empty($id) || !is_numeric($id)){
	    		echo '参数有误';
	    	}

	    	//判断当前用会员是否点过赞
	    	$query = $this->db->query("SELECT * FROM praise WHERE type=1 AND cid={$id} AND mid={$this->session->member->id}");
			$res = $query->row();

			if($res){

				//点过赞删除
				$this->db->query("DELETE FROM praise WHERE id={$res->id}");

				//同时评论表减1
				$this->db->query("UPDATE comment SET praise=praise-1 WHERE id={$id}");

				echo -1;
				
			}else{

				//未点过增加
				$data = array(
					'type' => 1,
					'mid' => $this->session->member->id,
				    'cid' => $id,
				    'createTime' => date('Y-m-d H:i:s')
				);
				$this->db->insert('praise', $data);

				//同时评论表加1
				$this->db->query("UPDATE comment SET praise=praise+1 WHERE id={$id}");

				echo 1;

			}

		}

	}

	//无刷新评论
	public function save(){

		$mid = $this->session->member->id;

		if($mid > 0){

			$aid = $this->input->post('id');

			//所属栏目
			$type = $this->input->post('type');
			switch ($type) {
	    		case 'activity':
	    			$v = 1;
	    			break;
	    		case 'story':
	    			$v = 2;
	    			break;
	    		case 'course':
	    			$v = 3;
	    			break;
	    		case 'slide':
	    			$v = 4;
	    			break;
	    	}
			
			$comment = $this->input->post('comment');

			//将内容中含有的表情替换成图片
			$content = preg_replace("/<emt[^>]*>(.*?)<\/emt>/is", "<img src='/public/home/images/face/$1.gif' />", $comment);

			$isReview = $this->input->post('isReview');
			if($isReview == 1){

				//内容还需查找替换 回复 xxxxxx：
				$content = preg_replace("/回复(.*?)：/is", "", $content);

				//不能回复自己????

				//回复当前留言者
				$data = array(
					'cid' => $aid,
				    'mid' => $mid,
				    'content' => $content,
				    'createTime' => date('Y-m-d H:i:s')
				);
				$bool = $this->db->insert('review', $data);

			}else{

				//单纯留言
				$data = array(
					'type' => $v,
					'aid' => $aid,
				    'mid' => $mid,
				    'content' => $content,
				    'createTime' => date('Y-m-d H:i:s')
				);
				$bool = $this->db->insert('comment', $data);

			}

		}

	}

}

?>