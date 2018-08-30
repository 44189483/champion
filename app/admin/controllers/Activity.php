<?php
/*
* Activity 志愿服务/公益活动
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
		$this->load->database();

		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}

		$this->load->library('pagination');
		$this->head_data['cname'] = __CLASS__;
		$this->load->view('templates/header.html',$this->head_data);
		$this->load->view('templates/menu.html',$this->head_data);

	}

	//活动列表
	public function index(){

		$now = date('Y-m-d H:i:s');

		$where = "WHERE 1=1";

		$search = '';

		$number = trim($this->input->get('number'));
		if(!empty($number)){
			$where .= " AND serviceNumber='{$number}'";
			$search .= " &serviceNumber='{$number}'";
		}
		
		$title = trim($this->input->get('title'));
		if(!empty($title)){
			$where .= " AND title LIKE '%{$title}%'";
			$search .= " &title='{$title}'";
		}

		$state = $this->input->get('state');
		if(!empty($state)){
			if($state == 1){//进行中
				$where .= " AND (startTime <= '{$now}' AND endTime >= '{$now}')";
			}else if($state == 3){//已结束
				$where .= " AND endTime < '{$now}'";
			}else{//正在招募
				$where .= " AND startTime > '{$now}'";
			}
		}

	    $config = array();
	    $config['per_page'] = 10; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    $query = $this->db->query("SELECT * FROM activity {$where}");
	    $result['total'] = $query->num_rows();

	    $query = $this->db->query("SELECT * FROM activity {$where} ORDER BY startTime DESC,id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();
	    foreach ($result['list'] as $key => $val) {

			if($val->startTime <= $now && $now <= $val->endTime){
				//进行中
				$result['list'][$key]->state = '进行中';
			}else if ($val->endTime < $now) {
				//已结束
				$result['list'][$key]->state = '已结束';
			}else{
				//正在招募
				$result['list'][$key]->state = '正在招募';
			}
	    	
	    }

	    $config['base_url']   = site_url('activity/index?').$search;
	    
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
	    	'state' => $state,
	    	'title' => $title,
	    	'number' => $number,
	        'rows' => $result['list'],
	        'total'  => $result['total'],
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links(),
	    );

		$this->load->view('activity/index.html',$data);
	}
	
	//添加活动
	public function add(){

		//统计当日订单总数累计并生成编号
		$date = date('Y-m-d');
		$start = $date.' 00:00:00';
		$end = $date.' 23:59:59';
		$que = $this->db->query("SELECT id FROM activity WHERE startTime BETWEEN '{$start}' AND '{$end}'");
		$total = $que->num_rows() + 1;
		$number = $total < 10 ? '0'.$total : $total;
		$data['serviceNumber'] = date('Ymd').$number;
		
		$this->load->view('activity/add.html',$data);

	}

	//编辑活动
	public function edit($id=null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$data = array();
		$query = $this->db->query("SELECT * FROM activity WHERE id={$id}");
	    $data = $query->row();
		$this->load->view('activity/edit.html',$data);

	}

	//保存活动
	public function save(){

		$id = $this->input->post('id');

		$type = $this->input->post('type');

		$number = $this->input->post('number');

		$title = $this->input->post('title');

		$sponsor = $this->input->post('sponsor');

		$organizer = $this->input->post('organizer');

		$address = $this->input->post('address');

		$scheme = $this->input->post('scheme');

		$need = $this->input->post('need');

		$other = $this->input->post('other');

		$nature = $this->input->post('nature');

		$recruitment = $this->input->post('recruitment');

		$start = $this->input->post('start');

		$end = $this->input->post('end');

		$status = $this->input->post('status') == '' ? 0 : 1;

		//计算天数
		$days = $this->diff_date($start, $end);

		//按活动天数计算活动总积分
		$query = $this->db->query("SELECT optionValue FROM baseoption WHERE optionType='integral' AND optionKey='base'");
	    $res = $query->row();//取出积分积数
		switch ($type) {
			case 1:
				//志愿服务活动
				$integral = $res->optionValue * $days;
				break;
			case 2:
				//中心参与/邀请
				$integral = $res->optionValue / 3 * $days;
				break;
			case 3:
				//线下志愿培训
				$integral = $res->optionValue * $days;
				break;
			case 4:
				//志愿服务会议
				$integral = $res->optionValue * $days;
				break;
		}

		$data = array(
			'type' => $type,
		    'title' => $title,
		    'serviceNumber' => $number,
		    'sponsor' => $sponsor,
		    'organizer' => $organizer,
		    'activityAddress' => $address,
		    'activityScheme' => $scheme,
		    'activityNature' => $nature,
		    'sportNeed' => $need,
		    'other' => $other,
		    'integral' => $integral,
		    'recruitment' => $recruitment,
		    'startTime' => $start,
		    'endTime' => $end,
		    'status' => $status,
		    'createTime' => date('Y-m-d H:i:s')
		);

		//文件存在判断
		if(!empty($_FILES["attchment"]["name"]) && is_uploaded_file($_FILES["attchment"]["tmp_name"])){

			//重命名
			$attchname = $_FILES["attchment"]["name"];
			$ext = substr($attchname,strripos($attchname,'.') + 1);
			$name = date('Ymd').rand(0,999).'.'.$ext;
			$config['file_name'] = $name;
			$config['upload_path'] = 'upload/activity/pic/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '0';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$this->upload->do_upload('attchment');
			$info = $this->upload->data();

			if(!$info){
		      $error = $this->upload->display_errors();
		      if($error = "The image you are attempting to upload doesn't fit into the allowed dimensions."){
		      	show_error('图片尺寸有误',null,'错误提示');	
		      	exit();
		      }
		    }

		    if($info && !empty($id)){//删除旧图
				$query = $this->db->query("SELECT pic FROM activity WHERE id={$id}");
	    		$row = $query->row(); 
				@unlink($row->pic);
		    }

		    $data['pic'] = $config['upload_path'].$name;

		}

		if(empty($id)){
			$query = $this->db->query("SELECT * FROM activity WHERE title='{$title}'");
	    	$row = $query->row(); 
			if($row){
				jump('该信息已存在',site_url('activity/add'));
				exit();
			}
			
			$bool = $this->db->insert('activity', $data);

			$groupId = $this->db->insert_id();

			$groupName = $title;

			$members = array('1');//默认ID为1的用户是创建管理者

			//创建群组即时通讯
			include '/API/rongcloud/WebIm.php';
			$rongcloud = new WebIm();
			$status = $rongcloud->createGroup($members,$groupId,$groupName);
			if($status == '200'){

				//创建组数据库
				$data = array(
					'id' => $groupId,
				    'name' => $groupName,
				    'memberCount' => 1,
				    'maxMemberCount' => 500,
				    'creatorId' => 1,
				    'type' => 1,
				    'timestamp' => time(),
				    'createdAt' => date('Y-m-d H:i:s')
				);
				$this->db->insert('groups', $data);

				//创建组成员数据库
				$data = array(
				    'groupId' => $groupId,
				    'memberId' => 1,
				    'role' => 1,
				    'show' => 1,
				    'timestamp' => time(),
				    'createdAt' => date('Y-m-d H:i:s')
				);
				$this->db->insert('group_members', $data);

			}

		}else{
			$this->db->where('id', $id);
			$bool = $this->db->update('activity', $data);
		}

		if($bool) {
            jump('操作成功',site_url('activity/index'));
        }else{
            jump('操作失败');
        }

	}

	//删除活动
	public function del($id = null){

		$pid = $this->input->post('id');

		$gid = $id;

		$bool = false;

		if($pid){//多删

			//需要解散组？

			$query = $this->db->query("SELECT * FROM activity WHERE id IN(".implode(',', $pid).")");
	    	$rows = $query->result();

			foreach ($rows as $k => $v) {
				@unlink($v->articleAttach);
			}

			$bool = $this->db->query("DELETE FROM activity WHERE id IN(".implode(',', $pid).")"); 

		}else if (!empty($gid)) {//单删

			//需要解散组？

			$query = $this->db->query("SELECT * FROM activity WHERE id={$gid}");
	    	$row = $query->row();
			//删除图片
			@unlink($row->articleAttach);

			$bool = $this->db->delete('activity', array('id' => $gid));

		}
		
		if($bool) {
            jump('操作成功',site_url('activity/index'));
        }else{
            jump('操作失败');
        }

	}

	//报名列表
	public function signup($aid = null){

		if(empty($aid) || !is_numeric($aid)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$que = $this->db->query("SELECT title FROM activity WHERE id={$aid}");
	    $res = $que->row();

	    $config = array();
	    $config['per_page'] = 5; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    $sql = "
			SELECT
				m.realname,
				s.id,
				s.mid,
				s.signature,
				s.status,
				s.createTime
			FROM
				signup s
			INNER JOIN
				users m
			ON
				s.mid=m.id
			WHERE
				s.aid={$aid}
	    ";

	    $query = $this->db->query($sql);
	    $result['total'] = $query->num_rows();

	    $query = $this->db->query($sql." ORDER BY s.id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();
	    
	    $config['base_url']   = site_url("activity/signup/{$aid}");//'admin.php/activity/index?';
	    
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

	    //获取没有参与活动的所有人员下拉列表
	    $sql = "SELECT id,realname FROM users WHERE level=1 AND NOT EXISTS (SELECT * FROM signup WHERE aid={$aid} AND mid=users.id)";
	    $query = $this->db->query($sql);
	    $select = $query->result_array();

	    $data = array(
	    	'aid' => $aid,
	    	'select' => $select,
	    	'title' => $res->title,
	        'rows' => $result['list'],
	        'total'  => $result['total'],
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links()
	    );

		$this->load->view('activity/signup.html',$data);
	}

	//保存报名人员
	public function savesignup(){

		$aid = $this->input->post('aid');
		if(empty($aid) || !is_numeric($aid)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$mids = $this->input->post('mids');
		if(empty($mids)){
			show_error('人员必选!',null,'错误提示');
			exit();
		}

		$query = $this->db->query("SELECT title,integral FROM activity WHERE id={$aid}");
    	$row = $query->row();

    	include '/API/rongcloud/WebIm.php';

		$rongcloud = new WebIm();

		foreach ($mids as $key => $val) {

			$data = array(
				'aid' => $aid,
			    'mid' => $val,
			    'status' => 1,
			    'createTime' => date('Y-m-d H:i:s')
			);
			$bool = $this->db->insert('signup', $data);

			if($bool){

				//会员加积分
				$this->db->query("UPDATE users SET integral=integral + {$row->integral} WHERE id={$val}");

				//拉入即时通讯群组
				$rongcloud->joinGroup($val,$aid,$row->title);

				//刷新群组
				$rongcloud->syncGroup($val,array($aid =>$row->title));

			}

		}

		jump('操作完成',site_url('activity/signup/'.$aid));

	}

	//删除报名
	public function delsignup($id = null){

		$sql = "
			SELECT
				a.id,
				a.title,
				a.integral, 
				s.mid 
			FROM 
				activity a
			INNER JOIN
				signup s
			ON
				a.id=s.aid
			WHERE 
				s.id={$id}
		";
		$query = $this->db->query($sql);
    	$row = $query->row();

    	//会员退出群
    	include '/API/rongcloud/WebIm.php';
		$rongcloud = new WebIm();
	    $rongcloud->kickGroup($row->mid,$row->title);

	    //刷新群组
		$rongcloud->syncGroup($row->mid,array($row->id  => $row->title));
    	
    	//会员积分扣除
    	$this->db->query("UPDATE users SET integral=integral - {$row->integral} WHERE id={$row->mid}");
		
		$bool = $this->db->delete('signup', array('id' => $id));

		if($bool) {
            jump('操作成功',site_url('activity/signup/'.$row->aid));
        }else{
            jump('操作失败');
        }

	}

	//活动资料列表
	public function lists($aid = null,$type = null){

		if(empty($aid) || !is_numeric($aid)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		//活动信息
		$que = $this->db->query("SELECT title FROM activity WHERE id={$aid}");
	    $res = $que->row();

	    $type = empty($type) ? 1 : $type;

	    $sql = "SELECT * FROM users_post WHERE aid={$aid} AND type={$type}";

	    $query = $this->db->query($sql);
	    $total = $query->num_rows();

		$config = array();
	    $config['per_page'] = 10; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）
	    
	    $query = $this->db->query($sql." ORDER BY id DESC LIMIT {$offset},{$config['per_page']}");
	    $result = $query->result();
	    foreach ($result as $key => $val) {

	    	if($val->mid == 0){
				$result[$key]->author = '官方';
				$result[$key]->portraitUri = '/public/home/images/logo.png';
			}else{
				$que1 = $this->db->query("SELECT nickname AS author,portraitUri FROM users WHERE id={$val->mid}");
				$res1 = $que1->row();
				$result[$key]->portraitUri = $res1->portraitUri;
				$result[$key]->author = $res1->author;
			}

	    }

	    $config['base_url']   = site_url('activity/lists/').$aid.'/'.$type;
	    
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

	    $config['total_rows'] = $total;//总条数
	    $config['num_links']  = 2;//页码连接数
	    $config['use_page_numbers'] = TRUE;//使用页码而不是offset
	    $config['use_page_titles']  = TRUE;
	    $config['page_query_string'] = TRUE;
	    $this->load->library('pagination');//加载ci pagination类
	    $this->pagination->initialize($config);
	   
	   	$data = array(
	    	'aid' => $aid,
	    	'type' => $type,
	    	'title' => $res->title,
	    	'total'  => $total,
	        'result' => $result,
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links(),
	    );

		$this->load->view('activity/lists.html',$data);

	}

	//添加活动资料
	public function addinfo($aid = null,$type = null){

		if(empty($aid) || !is_numeric($aid)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		if($type == 1){
			$title = "美文";
		}else if($type == 2){
			$title = "图片";
		}else if ($type == 3) {
			$title = "视频";
		}

		$data = array(
			'aid' => $aid,
			'type' => $type,
			'title' => $title
		);

		$this->load->view('activity/addinfo.html',$data);

	}

	//编辑活动资料
	public function editinfo($id = null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$query = $this->db->query("SELECT * FROM users_post WHERE id={$id}");
	    $row = $query->row();

	    if($row->type == 1){
			$title = "美文";
		}else if($row->type == 2){
			$title = "图片";
		}else if ($row->type == 3) {
			$title = "视频";
		}

	    $data = array(
			'title' => $title,
			'row' => $row
		);
		$this->load->view('activity/editinfo.html',$data);

	}

	//保存活动
	public function saveinfo(){

		$id = $this->input->post('id');

		$aid = $this->input->post('aid');

		$title = $this->input->post('title');

		$content = $this->input->post('content');

		$type = $this->input->post('type');

		$data = array(
			'mid' => 0,
			'aid' => $aid,
			'type' => $type,
		    'title' => $title,
		    'content' => $content,
		    'status' => 1,
		    'createTime' => date('Y-m-d H:i:s')
		);

		if(empty($id)){
			
			$bool = $this->db->insert('users_post', $data);

		}else{
			$this->db->where('id', $id);
			$bool = $this->db->update('users_post', $data);
		}

		if($bool) {
            jump('操作成功',site_url("activity/lists/{$aid}/{$type}"));
        }else{
            jump('操作失败');
        }

	}

	//AJAX 图片批量上传
	public function upfiles($aid = null){

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		if(empty($aid) || !is_numeric($aid)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		// Support CORS
		// header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit; // finish preflight CORS requests here
		}


		if ( !empty($_REQUEST[ 'debug' ]) ) {
		    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
		    if ( $random === 0 ) {
		        header("HTTP/1.0 500 Internal Server Error");
		        exit;
		    }
		}

		// header("HTTP/1.0 500 Internal Server Error");
		// exit;


		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		usleep(5000);

		// Settings
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = 'upload_tmp';
		$uploadDir = 'upload/member/pic/';

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds


		// Create target dir
		if (!file_exists($targetDir)) {
		    @mkdir($targetDir);
		}

		// Create target dir
		if (!file_exists($uploadDir)) {
		    @mkdir($uploadDir);
		}

		// Get a file name
		if (isset($_REQUEST["name"])) {
		    $fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
		    $fileName = $_FILES["file"]["name"];
		} else {
		    $fileName = uniqid("file_");
		}

		$md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$md5File = $md5File ? $md5File : array();

		if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
		    die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


		// Remove old temp files
		if ($cleanupTargetDir) {
		    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		    }

		    while (($file = readdir($dir)) !== false) {
		        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		        // If temp file is current file proceed to the next
		        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
		            continue;
		        }

		        // Remove temp file if it is older than the max age and is not the current file
		        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
		            @unlink($tmpfilePath);
		        }
		    }
		    closedir($dir);
		}


		// Open temp file
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES)) {
		    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		    }

		    // Read binary input stream and append it to temp file
		    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		    }
		} else {
		    if (!$in = @fopen("php://input", "rb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		    }
		}

		while ($buff = fread($in, 4096)) {
		    fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

		$index = 0;
		$done = true;
		for( $index = 0; $index < $chunks; $index++ ) {
		    if ( !file_exists("{$filePath}_{$index}.part") ) {
		        $done = false;
		        break;
		    }
		}
		if ( $done ) {
		    if (!$out = @fopen($uploadPath, "wb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		    }

		    if ( flock($out, LOCK_EX) ) {
		        for( $index = 0; $index < $chunks; $index++ ) {
		            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
		                break;
		            }

		            while ($buff = fread($in, 4096)) {
		                fwrite($out, $buff);
		            }

		            @fclose($in);
		            @unlink("{$filePath}_{$index}.part");
		        }

		        flock($out, LOCK_UN);
		    }
		    @fclose($out);
		}

		// Return Success JSON-RPC response
		//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

		$data = array(
			'mid' => 0,
		    'title' => '活动图片',
		    'type' => 2,
		    'aid' => $aid,
		    'content' => $uploadPath,
		    'status' => 1,
		    'createTime' => date('Y-m-d H:i:s')
		);

		$this->db->insert('users_post', $data);

	}

	//删除图片
	public function delpic($id){

		$bool = false;

		$query = $this->db->query("SELECT aid,content FROM users_post WHERE id={$id}");
    	$row = $query->row();
		//删除图片
		@unlink($row->content);

		$bool = $this->db->delete('users_post', array('id' => $id));
		
		if($bool) {
            jump('操作成功',site_url("activity/lists/{$row->aid}/2"));
        }else{
            jump('操作失败');
        }

	}

	//删除活动
	public function delinfo($id = null){

		$query = $this->db->query("SELECT * FROM users_post WHERE id={$id}");
    	$row = $query->row();
		
		$bool = $this->db->delete('users_post', array('id' => $id));

		if($bool) {
            jump('操作成功',site_url("activity/lists/{$row->aid}/{$row->type}"));
        }else{
            jump('操作失败');
        }

	}

	/**
	 * 求两个日期之间相差的天数
	 * @param string $date1
	 * @param string $date2
	 * @return number 返回向上接近取整
	 */
	public function diff_date($date1, $date2){

	    if($date1 > $date2){
	        $startTime = strtotime($date1);
	    	$endTime = strtotime($date2);
	    }else{
		    $startTime = strtotime($date2);
		    $endTime = strtotime($date1);
	    }

	    $diff = $startTime - $endTime;
	    $day = $diff / 86400;
	    return ceil($day);

	}

}
?>