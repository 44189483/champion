<?php
/*
* Activity 志愿者服务招募
* @package	Activity
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class Activity extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->helper('func_helper');
		$this->load->database();

		$this->header = array(
			'cmenu' => __CLASS__,
			'member' => $this->session->member
		);

	}

	//判断是否登录
	private function isMemberLogin(){

		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

	} 

	//活动列表
	public function Index($type = null){

		$this->isMemberLogin();

		$now = date('Y-m-d H:i:s');

		$file = $this->input->get('file');

		$sql = "SELECT id,title,pic,integral,SUBSTRING(startTime,1,10) AS startDate,SUBSTRING(endTime,1,10) AS endDate FROM activity WHERE status=1 ";

		if($type == 'begain'){//进行中
			$sql .= " AND (startTime <= '{$now}' AND endTime >= '{$now}')";
		}else if($type == 'over'){//已结束
			$sql .= " AND endTime < '{$now}'";
		}else{//正在招募
			$sql .= " AND startTime > '{$now}'";
		}

		$sql .= " ORDER BY endTime DESC,id DESC";

		//无刷新加载列表
		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page -1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

		    $sql .= " LIMIT {$offset},{$per_page}";

		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->title = strip_tags(utfSubstr($val->title, 0, 25));
		    	if($this->session->member->id > 0){
			    	$que = $this->db->query("SELECT id FROM signup WHERE aid={$val->id} AND mid={$this->session->member->id}");
			    	$res = $que->row();
		    	}
		    	$result[$key]->isSign = $res == true ? 1 : 0;
		    }

		    if($result){
		    	echo $json = json_encode($result);
		    }

		}else{

			$query = $this->db->query($sql);
		    $total = $query->num_rows();
		    
		    $data = array(
		    	'type' => $type,
		    	'total' => $total
		    );

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('activity/list.html',$data);
			$this->load->view('templates/footer.html');

		}
	    
	}

	//搜索结果页 活动列表
	public function search(){

		$now = date('Y-m-d H:i:s');

		//查询内容
		$keyword = $data['keyword'] = $this->input->get('keyword');

		$file = $this->input->get('file');

		$per_page = 5; //每页显示的数据数

		//获取当前分页页码数
	    $current_page = intval($this->input->get('page')); 
	    //设置偏移量 限定
	    $offset = ($current_page - 1) * $per_page;

	    $sql = "SELECT id,title,pic,integral,startTime,endTime FROM activity WHERE status=1 AND title LIKE '%{$keyword}%'  ORDER BY endTime DESC,id DESC";

	    $query = $this->db->query($sql);
	    $data['total'] = $query->num_rows();

		if($file == 'ajax'){
			$sql .= " LIMIT {$offset},{$per_page}";
		}

	    $query = $this->db->query($sql);
	    $data['list'] = $query->result();
	    foreach ($data['list'] as $key => $val) {
	    	
	    	$data['list'][$key]->title = utfSubstr($val->title,0,25);
	    	if($this->session->member->id > 0){
		    	$que = $this->db->query("SELECT id FROM signup WHERE aid={$val->id} AND mid={$this->session->member->id}");
		    	$res = $que->row();	
	    	}

	    	//,SUBSTRING(startTime,1,10) AS startDate,SUBSTRING(endTime,1,10)

	    	//活动状态判断
	    	if($val->startTime > $now){//正在招募
	    		$data['list'][$key]->status = 'ready';
	    	}else if($val->endTime < $now){//已结束
	    		$data['list'][$key]->status = 'over';
	    	}else if($val->startTime <= $now && $val->endTime >= $now){
	    		$data['list'][$key]->status = 'begain';
	    	}

	    	$data['list'][$key]->startDate = substr($val->startTime,0,10);

	    	$data['list'][$key]->endDate = substr($val->endTime,0,10);

	    	$data['list'][$key]->isSign = $res == true ? 1 : 0;

	    }

	    if($file == 'ajax'){

	    	if($data['list']){
		    	echo $json = json_encode($data['list']);
		    }

	    }else{
	    	$this->load->view('templates/header.html',$this->header);
			$this->load->view('activity/search.html',$data);
			$this->load->view('templates/footer.html');
	    }

	}

	//活动详情
	public function detail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		$now = date('Y-m-d H:i:s');

		//详情
		$query = $this->db->query("SELECT * FROM activity WHERE id='{$id}'");
    	$res = $query->row();

    	if(!$res){
    		show_error('信息不存在',null,'提示');
    	}

    	if($res->startTime <= $now && $res->endTime >= $now){//进行中
    		$res->state = 'begain';
    	}else if($res->endTime < $now){//已结束
    		$res->state = 'over';
    	}else{//正在招募
    		$res->state = 'active';
    	}

    	//查询已报名
    	$sql = "
    		SELECT 
    			m.id,
    			m.nickname,
    			m.portraitUri 
    		FROM 
    			signup s
    		INNER JOIN
    			users m
    		ON
    			s.mid=m.id
    		WHERE 
    			s.aid={$id} 
    			AND
    			s.status=1
    			ORDER BY s.id DESC
    	";
    	$q = $this->db->query($sql);
	    $res->members = $q->result();

    	if($this->session->member){
	    	$que = $this->db->query("SELECT id,status FROM signup WHERE aid={$res->id} AND mid={$this->session->member->id}");
	    	$result = $que->row();

	    	//当前用户是否已报名
    		$res->isSign = $result == true ? 1 : 0;
	    }else{
	    	$res->isSign = 0;
	    }

    	//当前用户是否已审核报名
    	$res->isStatus = empty($result->status) ? 0 : 1;

    	//报名人数
    	$res->total = count($res->members);

    	//标记是否从搜索页面进来 好返回用
    	$res->sign = $this->uri->segment(4);

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('activity/detail.html',$res);
		
	}

	//手动签名生成图片
	public function signature($id){

		$this->isMemberLogin();

		$mid = $this->session->member->id;
		if(empty($mid)){
			show_error('参数有误',null,'错误提示');
		}

		//上传图片
		$base64_image_content = $this->input->post('base64img');
		
		$output_directory = 'upload/activity/signature/'; //设置图片存放路径

		//检查并创建图片存放目录
		if (!file_exists($output_directory)) {
		    mkdir($output_directory, 0777);
		}

		//根据base64编码获取图片类型
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
		    $image_type = $result[2]; //data:image/jpeg;base64,
		    $output_file = $output_directory.md5(time()).'.'. $image_type;
		}

		//将base64编码转换为图片编码写入文件
		$image_binary = base64_decode(str_replace($result[1], '', $base64_image_content));
		if (file_put_contents($output_file, $image_binary)) {

		    $data = array(
		    	'aid' => $id,
		    	'mid' => $mid,
			    'signature' => $output_file,
			    'createTime' => date('Y-m-d H:i:s')
			);

			$bool = $this->db->insert($this->db->dbprefix('signup'), $data);

			if($bool){
				echo 1;
			}else{
				echo '报名失败';
			}

		}else{
			echo '图片上传失败';
		}

	}

	//上传资料
	public function savedata(){

		$this->isMemberLogin();

		$aid = $this->uri->segment(3);

		if(empty($aid) || !is_numeric($aid)){
    		show_error('参数有误',null,'提示');
    	}

		$id = $this->session->member->id;

		if($this->input->post('act') == 'save'){

			$title = $this->input->post('title');

			$type = $this->input->post('type');

			if($type == 'video'){

				$type = 3;

				//优酷视频网址处理 提取ID 存IFRAME
				
				$url = parse_url($this->input->post('content1'));
				$content = preg_replace("/\/video\/id_(.*?)\.html/is", "<iframe height='100%' width='100%' src='http://player.youku.com/embed/$1' frameborder=0 'allowfullscreen'></iframe>", $url['path']);

			}else if($type == 'pic'){

				//上传图片
				$base64_image_content = $this->input->post('base64img');
				$output_directory = 'upload/member/pic/'; //设置图片存放路径

				/* 检查并创建图片存放目录 */
				if (!file_exists($output_directory)) {
				    mkdir($output_directory, 0777);
				}

				/* 根据base64编码获取图片类型 */
				if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
				    $image_type = $result[2]; //data:image/jpeg;base64,
				    $output_file = $output_directory.md5(time()).'.'. $image_type;
				}

				/* 将base64编码转换为图片编码写入文件 */
				$image_binary = base64_decode(str_replace($result[1], '', $base64_image_content));
				if (file_put_contents($output_file, $image_binary)) {

				    $type = 2;
					$content = $output_file;

				}else{
					echo '图片上传失败';
				}

			}else if($type == 'text'){

				$type = 1;

				$content = $this->input->post('content');

			}

			$data = array(
				'mid' => $id,
			    'title' => $title,
			    'type' => $type,
			    'aid' => $aid,
			    'content' => $content,
			    'createTime' => date('Y-m-d H:i:s')
			);

			$bool = $this->db->insert('users_post', $data);

			if($bool){
				echo 1;
			}else{
				echo '发布失败';
			}

		}else{
			$this->load->view('templates/header.html',$this->header);
			$this->load->view('activity/shangchuan.html',array('aid'=>$aid));
		}

	}

	//我上传的资料
	public function myupload(){

		$this->isMemberLogin();

		$aid = $this->uri->segment(3);

		if(empty($aid) || !is_numeric($aid)){
    		show_error('参数有误',null,'提示');
    	}

    	$type = $this->uri->segment(4);

		if(empty($type) || !is_numeric($type)){
    		show_error('参数有误',null,'提示');
    	}

		$id = $this->session->member->id;

		if($type == 2){
			//图片
			$sql = "SELECT id,content AS image,SUBSTRING(createTime,1,10) AS date,praise FROM users_post";
		}else{
			//视频或文章
			$sql = "SELECT id,title,content,praise,createTime,SUBSTRING(createTime,1,10) AS date FROM users_post";
		}

		$query = $this->db->query($sql." WHERE aid={$aid} AND mid={$id} AND type={$type} ORDER BY id DESC");

		$data['total'] = $query->num_rows();

		if($type == 2){
			//图片
			$query = $this->db->query($sql." WHERE aid={$aid} AND mid={$id} AND type={$type} ORDER BY id DESC LIMIT 0,5");
			$data['result'] = $query->result();
			foreach ($data['result'] as $key => $val) {
				$data['result'][$key]->author = $this->session->member->nickname;
				//判断当前用会员是否点过收藏
		    	$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$val->id} AND mid={$this->session->member->id}");    	
				$row = $que->row();

				$data['result'][$key]->isCollect = $row == true ? 1 : 0;

				//判断当前会员是否点过赞
	    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
	    		$zan_num = $zan_query->num_rows();

	    		$data['result'][$key]->isPraise = $zan_num > 0 ? 1 : 0;
			}
		}

		//项目主ID
		$data['aid'] = $aid;

		//视频 图片 文字
		$data['type'] = $type;	

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('activity/myupload.html',$data);
		$this->load->view('templates/footer.html');

	}

	//我上传的资料 ajax
	public function myajaxdata($aid,$type){

		$this->isMemberLogin();

		$per_page = 5; //每页显示的数据数
	    $current_page = intval($this->input->get('page')); //获取当前分页页码数
	    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    if($type == 2){
	    	//图片信息
	    	$sql = "
		    	SELECT
		    		id, 
			    	content AS image,
			    	SUBSTRING(createTime,1,10) AS date,
			    	praise 
		    	FROM 
		    		users_post 
		    	WHERE 
		    			mid={$this->session->member->id} 
		    		AND 
		    			aid={$aid} 
		    		AND 
		    			type=2
		    ";
	    }else{
	    	//视频或文字信息
	    	$sql = "
		    	SELECT
		    		id, 
			    	title,
			    	content,
			    	SUBSTRING(createTime,1,10) AS date,
			    	praise 
		    	FROM 
		    		users_post 
		    	WHERE 
		    			mid={$this->session->member->id} 
		    		AND 
		    			aid={$aid} 
		    		AND 
		    			type={$type}
		    ";
	    }

		$query = $this->db->query($sql." ORDER BY id DESC LIMIT {$offset},{$per_page}");
		$res = $query->result();

		foreach ($res as $key => $val) {
			$res[$key]->author = $this->session->member->nickname;
			if($type == 1){
				$res[$key]->content = utfSubstr(strip_tags($val->content), 0, 100);
			}

			//判断当前用会员是否点过收藏
	    	$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$val->id} AND mid={$this->session->member->id}");    	
			$row = $que->row();

			$res[$key]->isCollect = $row == true ? 1 : 0;

			//判断当前会员是否点过赞
    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
    		$zan_num = $zan_query->num_rows();

    		$res[$key]->isPraise = $zan_num > 0 ? 1 : 0;

		}

		if($res){
			echo $json = json_encode($res);
		}

	}

	//我上传的 文章详情
	public function myuploaddetail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

    	$sql = "
    		SELECT
    			m.nickname, 
    			p.* 
    		FROM 
    			users_post p
    		INNER JOIN
    			users m
    		ON
    			p.mid=m.id
    		WHERE 
    			p.id={$id}
    	";

		//详情
		$query = $this->db->query($sql);
    	$res = $query->row();

    	if(!$res){
    		show_error('信息不存在',null,'提示');
    	}

    	//判断当前用会员是否点过收藏
    	$que = $this->db->query("SELECT * FROM users_collect WHERE type=2 AND aid={$id} AND mid={$this->session->member->id}");    	
		$row = $que->row();

		$res->isCollect = $row == true ? 1 : 0;

		//判断当前会员是否点过赞
		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$id} AND mid={$this->session->member->id}");
		$zan_num = $zan_query->num_rows();

		$res->isPraise = $zan_num > 0 ? 1 : 0;

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('activity/myuploaddetail.html',$res);
		
	}

	//点赞
	public function praise(){

		$this->isMemberLogin();

    	//评论ID
    	$id = $this->input->post('id');

    	if(empty($id) || !is_numeric($id)){
    		echo '参数有误';
    	}

    	//判断当前用会员是否点过赞
    	$query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$id} AND mid={$this->session->member->id}");
		$res = $query->row();

		if($res){

			//点过赞删除
			$this->db->query("DELETE FROM praise WHERE id={$res->id}");

			//同时评论表减1
			$this->db->query("UPDATE users_post SET praise=praise-1 WHERE id={$id}");

			echo -1;
			
		}else{

			//未点过增加
			$data = array(
				'type' => 2,
				'mid' => $this->session->member->id,
			    'cid' => $id,
			    'createTime' => date('Y-m-d H:i:s')
			);
			$this->db->insert('praise', $data);

			//同时评论表加1
			$this->db->query("UPDATE users_post SET praise=praise+1 WHERE id={$id}");

			echo 1;

		}

	}

	//用户收藏文章/图片/视频
	public function collect(){

		$this->isMemberLogin();

    	//评论ID
    	$id = $this->input->post('id');

    	if(empty($id) || !is_numeric($id)){
    		echo '参数有误';
    	}

    	//判断当前用会员是否点过收藏
    	$query = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$id} AND mid={$this->session->member->id}");
		$res = $query->row();

		if($res){

			//点过收藏删除
			$this->db->query("DELETE FROM users_collect WHERE id={$res->id}");

			echo -1;
			
		}else{

			//未点过增加
			$data = array(
				'type' => 1,
				'mid' => $this->session->member->id,
			    'aid' => $id,
			    'createTime' => date('Y-m-d H:i:s')
			);
			$this->db->insert('users_collect', $data);

			echo 1;

		}

	}

	//发稿规则
	public function rule(){

		$this->isMemberLogin();

		$query = $this->db->query("SELECT content FROM article WHERE type=5");
	    $res = $query->row();

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('activity/rule.html',$res);
		
	}
	
}
?>