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

	//列表
	public function Index(){

		//$this->isMemberLogin();

		$type = $this->uri->segment(3);

		$file = $this->input->get('file');

		if(empty($type)){
    		show_error('参数有误',null,'提示');
    	}

		if($type == 'zx'){
			//媒体资讯
			$sql = "
				SELECT
					m.id AS mid,
					m.realname,
					a.id,
					a.type,
					a.title,
					a.attach,
					a.content,
					a.createTime
				FROM
					article a
				LEFT JOIN
					users m
				ON 
					a.mid=m.id
				WHERE
					a.type IN(1,2)
				ORDER BY a.id DESC
			";	
		}else if($type == 'zl'){
			//活动资料
			$now = date('Y-m-d H:i:s');
			$sql = "
				SELECT 
					id,
					title,
					pic,
					pic,
					SUBSTRING(startTime,1,10) AS startDate,
					SUBSTRING(endTime,1,10) AS endDate 
				FROM 
					activity a 
				WHERE 
					status=1 AND endTime < '{$now}'
					ORDER BY id DESC
			";
		}

		//无刷新加载列表
		if($file == 'ajax'){

			$per_page = 5;

			//获取当前分页页码数
			$current_page = intval($this->input->get('page'));

		    //设置偏移量
		    $offset = ($current_page - 1) * $per_page; 

			$sql .= " LIMIT {$offset},{$per_page}";
		    
		    $query = $this->db->query($sql);
		    $result = $query->result();
			foreach ($result as $key => $val) {

		    	if($type == 'zx'){

		    		$result[$key]->title = strip_tags(utfSubstr($val->title,0,40));

		    		$result[$key]->realname = $val->mid == 0 ? '官方' : $result[$key]->realname;

		    		$result[$key]->attach = $val->attach == '' ? 'public/home/images/nopic.jpg' : $val->attach;
		    		
		    		$result[$key]->content = strip_tags(utfSubstr($val->content,0,50));

		    	}else{
		    		$result[$key]->pic = $val->pic == '' ? 'public/home/images/nopic.jpg' : $val->pic;
		    	}
		    }
		    if($result){
		    	echo $json = json_encode($result);
		    }

		}else{

			//统计所有总数
			$query = $this->db->query($sql);
		    $total = $query->num_rows();

		    $data = array(
		    	'class' => $type,
		    	'total' => $total
		    );

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('story/list.html',$data);
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

	    $sql = "
	    	SELECT 
    			m.id,
				m.realname,
				a.id,
				a.type,
				a.title,
				a.attach,
				a.content,
				a.createTime
			FROM
				article a
			INNER JOIN
				users m
			ON 
				a.mid=m.id
			WHERE
				a.type IN(1,2) AND a.title LIKE '%{$keyword}%'
			ORDER BY a.id DESC
		";

		//统计所有总数
		$query = $this->db->query($sql);
	    $result['total'] = $query->num_rows();

		if($file == 'ajax'){
			$sql .= " LIMIT {$offset},{$per_page}";
		}

		$query = $this->db->query($sql);
		$result['list'] = $query->result();
	    foreach ($result['list'] as $key => $val) {
	    	$result['list'][$key]->attach = $val->attach == '' ? 'public/home/images/nopic.jpg' : $val->attach;
	    	$result['list'][$key]->content = strip_tags(utfSubstr($val->content,0,80));
	    }

	    if($file == 'ajax'){

	    	if($result['list']){
		    	echo $json = json_encode($result['list']);
		    }

	    }else{
	    	$this->load->view('templates/header.html',$this->header);
			$this->load->view('story/search.html',$result);
			$this->load->view('templates/footer.html');
	    }

	}

	//资讯详情
	public function zxdetail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT id,title,attach,content,type FROM article WHERE id='{$id}'");
    	$res = $query->row();

    	if(!$res){
    		show_error('信息不存在',null,'提示');
    	}

    	//标记是否从搜索页面进来 好返回用
    	$res->sign = $this->uri->segment(4);

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('story/zxdetail.html',$res);
		
	}


	//活动详情
	public function zldetail(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		//详情
		$query = $this->db->query("SELECT * FROM activity WHERE id='{$id}'");
    	$res = $query->row();

    	if(!$res){
    		show_error('信息不存在',null,'提示');
    	}

    	if($this->session->member){
	    	$que = $this->db->query("SELECT id FROM signup WHERE aid={$id} AND mid={$this->session->member->id}");
	    	$row = $que->row();
	    	$res->isSign = $row == true ? 1 : 0;
    	}else{
    		$res->isSign = 0;
    	}
   
    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('story/zldetail.html',$res);

	}

	//活动美文列表
	public function article(){

		$this->isMemberLogin();

		$id = $this->uri->segment(3);

		$file = $this->input->get('file');

		if(empty($id)){
    		show_error('参数有误',null,'提示');
    	}

    	$sql = "SELECT * FROM users_post WHERE aid={$id} AND type=1 ORDER BY id DESC";

    	if($file == 'ajax'){

    		$per_page = 5;

			//获取当前分页页码数
			$current_page = intval($this->input->get('page'));

		    //设置偏移量
		    $offset = ($current_page - 1) * $per_page; 

		    $sql .= " LIMIT {$offset},{$per_page}";
		    
		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->content = utfSubstr(strip_tags($val->content),0,60);
		    }
		    
		    if($result){
		    	echo $json = json_encode($result);
		    }

    	}else{
	    
		    $query = $this->db->query($sql);
		    $total = $query->num_rows();
		    	    
		    $data = array(
		    	'id' => $id,
		    	'total' => $total
		    );

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('story/article.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//活动美文详情
	public function articledetail(){

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

    	if($this->session->member){

    		//判断当前用会员是否点过收藏
    		$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$id} AND mid={$this->session->member->id}");    	
			$row = $que->row();

			$res->isCollect = $row == true ? 1 : 0;

			//判断当前会员是否点过赞
    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$id} AND mid={$this->session->member->id}");
    		$zan_num = $zan_query->num_rows();

    		$res->isPraise = $zan_num > 0 ? 1 : 0;

    	}else{
    		$res->isCollect = 0;
    		$res->isPraise = 0;
    	}

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('story/articledetail.html',$res);
		
	}

	//图片集
	public function pics(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

    	//获取活动主题
    	$que = $this->db->query("SELECT title FROM activity WHERE id={$id}");
    	$res = $que->row();
    	$data['title'] = $res->title;

    	/*
    	$sql = "
			SELECT 
	    		m.nickname AS author,
	    		m.portraitUri,
	    		p.id,
	    		p.content AS image, 
	    		SUBSTRING(p.createTime,1,10) AS date, 
	    		p.praise 
	    	FROM 
	    		users_post p
	    	LEFT JOIN
	    		users m
	    	ON 
	    		p.mid=m.id
	    	WHERE 
	    			aid={$id} 
	    		AND 
	    			type=2 
	    	ORDER BY p.id DESC
	    	LIMIT 0,5
    	";
    	*/
    
    	$sql = "
			SELECT 
	    		id,
	    		mid,
	    		content AS image, 
	    		SUBSTRING(createTime,1,10) AS date, 
	    		praise 
	    	FROM 
	    		users_post
	    	WHERE 
	    			aid={$id} 
	    		AND 
	    			type=2 
	    	ORDER BY id DESC
	    	LIMIT 0,5
    	";
		
		$query = $this->db->query($sql);

		$data['result'] = $query->result();
		foreach ($data['result'] as $key => $val) {

			//获取发布作者
			if($val->mid == 0){
				$data['result'][$key]->author = '官方';
				$data['result'][$key]->portraitUri = '/public/home/images/logo.png';
			}else{
				$que = $this->db->query("SELECT nickname AS author,portraitUri FROM users WHERE id={$val->mid}");
				$res = $que->row();
				$data['result'][$key]->portraitUri = $res->portraitUri;
				$data['result'][$key]->author = $res->author;
			}

			//判断图片是否收藏
			$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$val->id}");
			$num = $que->num_rows();

			$data['result'][$key]->collect = $num > 0 ? 1 : 0;

			//判断当前会员是否点过赞
    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
    		$zan_num = $zan_query->num_rows();

    		$data['result'][$key]->isPraise = $zan_num > 0 ? 1 : 0;

		}

		$data['id'] = $id;

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('story/pics.html',$data);
		$this->load->view('templates/footer.html');

	}

	// ajax 图片集
	public function ajaxpics(){

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		$per_page = 5; //每页显示的数据数
	    $current_page = intval($this->input->get('page')) - 1; //获取当前分页页码数
    
	    $offset = $current_page * $per_page; //设置偏移量

	    $sql = "
	    	SELECT 
	    		id,
	    		mid,
	    		content AS image, 
	    		SUBSTRING(createTime,1,10) AS date, 
	    		praise 
	    	FROM 
	    		users_post
	    	WHERE 
	    			aid={$id} 
	    		AND 
	    			type=2 
	    	ORDER BY id DESC
	    	LIMIT {$offset},{$per_page}
	    ";
	    
		$query = $this->db->query($sql);

		$res = $query->result();
		foreach ($res as $key => $val) {

			//获取发布作者
			if($val->mid == 0){
				$res[$key]->author = '官方';
				$res[$key]->portraitUri = '/public/home/images/logo.png';
			}else{
				$que = $this->db->query("SELECT nickname AS author,portraitUri FROM users WHERE id={$val->mid}");
				$res = $que->row();
				$res[$key]->portraitUri = $res->portraitUri;
				$res[$key]->author = $res->author;
			}

			//判断图片是否收藏
			$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$val->id}");
			$num = $que->num_rows();

			$res[$key]->collect = $num > 0 ? 1 : 0;

			//判断当前会员是否点过赞
    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
    		$zan_num = $zan_query->num_rows();

    		$res[$key]->isPraise = $zan_num > 0 ? 1 : 0;

		}

		if($res){
			echo $json = json_encode($res);
		}else{
			echo 0;
		}

	}

	//活动视频
	public function video(){

		$this->isMemberLogin();

		$id = $this->uri->segment(3);

		$file = $this->input->get('file');

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

    	$sql = "
	    	SELECT
	    		id,
	    		mid,
	    		title,
	    		content, 
	    		SUBSTRING(createTime,1,10) AS date, 
	    		praise 
	    	FROM 
	    		users_post
	    	WHERE 
	    			aid={$id} 
	    		AND 
	    			type=3 
	    	ORDER BY id DESC 
	    ";

    	if($file == 'ajax'){

    		$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
	    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量

		    $sql .= " LIMIT {$offset},{$per_page}";
		    
			$query = $this->db->query($sql);

			$res = $query->result();
			foreach ($res as $key => $val) {

				//获取发布作者
				if($val->mid == 0){
					$res[$key]->author = '官方';
					$res[$key]->portraitUri = '/public/home/images/logo.png';
				}else{
					$que = $this->db->query("SELECT nickname AS author,portraitUri FROM users WHERE id={$val->mid}");
					$row = $que->row();
					$res[$key]->portraitUri = $row->portraitUri;
					$res[$key]->author = $row->author;
				}

				//判断视频是否收藏
				$que = $this->db->query("SELECT * FROM users_collect WHERE type=1 AND aid={$val->id} AND mid={$this->session->member->id}");
				$num = $que->num_rows();
				$res[$key]->collect = $num > 0 ? 1 : 0;

				//判断当前会员是否点过赞
	    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
	    		$zan_num = $zan_query->num_rows();
	    		$res[$key]->isPraise = $zan_num > 0 ? 1 : 0;
			}

			echo $json = json_encode($res);

    	}else{

	    	//获取活动主题
	    	$que = $this->db->query("SELECT title FROM activity WHERE id={$id}");
	    	$res = $que->row();
	    	$data['title'] = $res->title;
			
			$query = $this->db->query($sql);
			$data['total'] = $query->num_rows();		
			$data['id'] = $id;

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('story/video.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//点赞用户发布的图片/文章/视频等
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

	//用户收藏
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

	//我要发稿
	public function mypress(){

		$this->isMemberLogin();

		if($this->input->post('act') == 'save'){

			$title = $this->input->post('title');

			$type = $this->input->post('radio');

			if($type == 1){
				$content = $this->input->post('content');
			}else{
				$content = $this->input->post('link');
			}

			$mid = $this->session->member->id;

			//上传图片
			$base64_image_content = $this->input->post('base64img');
			$output_directory = 'upload/article/'; //设置图片存放路径

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
			file_put_contents($output_file, $image_binary);

			$data = array(
		    	'mid' => $mid,
		    	'type' => $type,
		    	'title' => $title,
		    	'content' => $content,
		    	'attach' => $output_file,			    
			    'createTime' => date('Y-m-d H:i:s')
			);

			$bool = $this->db->insert($this->db->dbprefix('article'), $data);

			if($bool){
				echo 1;
			}else{
				echo 0;
			}

		}else{

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('story/mypress.html');

		}

	}

	//上传资料
	public function savedata(){

		$this->isMemberLogin();

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		$mid = $this->session->member->id;

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
				'id' => $id,
				'mid' => $mid,
			    'title' => $title,
			    'type' => $type,
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
			$this->load->view('story/shangchuan.html',array('id'=>$id));
		}

	}

	//发稿规则
	public function rule(){

		$this->isMemberLogin();

		$query = $this->db->query("SELECT content FROM article WHERE type=5");
	    $res = $query->row();

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('story/rule.html',$res);
		
	}
	
}
?>