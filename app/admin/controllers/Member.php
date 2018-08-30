<?php
/*
* 会员 
* @package	Member
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Member extends CI_Controller{

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

	public function index(){

		$where = "WHERE 1=1";

		$phone = trim($this->input->get('phone'));
		if(!empty($phone)){
			$where .= " AND phone='{$phone}'";
		}

		$search = '';
		$level = $this->input->get('level');
		if($level != ''){
			$where .= " AND level='{$level}'";
			$search .= "level={$level}";
		}

	    $config = array();
	    $config['per_page'] = 10; //每页显示的数据数
	    $current_page = intval($this->input->get('per_page')); //获取当前分页页码数
	    //page还原
	    if(0 == $current_page){
	      	$current_page = 1;
	    }
	    $offset = ($current_page - 1 ) * $config['per_page']; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

	    $query = $this->db->query("SELECT * FROM users {$where}");
	    $result['total'] = $query->num_rows();

	    $query = $this->db->query("SELECT * FROM users {$where} ORDER BY id DESC LIMIT {$offset},{$config['per_page']}");
	    $result['list'] = $query->result();

	    $config['base_url'] = site_url('member?').$search;
	    
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
	    	'level' => $level,
	    	'phone' => $phone,
	        'rows' => $result['list'],
	        'total'  => $result['total'],
	        'current_page' => $current_page,
	        'per_page' => $config['per_page'],
	        'page'  => $this->pagination->create_links(),
	    );
		$this->load->view('member/index.html',$data);
	}

	public function add(){
		$this->load->view('member/add.html');
	}

	public function edit($id=null){

		if(empty($id) || !is_numeric($id)){
			show_error('参数非法!',null,'错误提示');
			exit();
		}

		$data = array();
		$query = $this->db->query("SELECT * FROM users WHERE id={$id}");
    	$data = $query->row();
	
		$this->load->view('member/edit.html',$data);

	}

	public function save(){

		$id = $this->input->post('id');

		$nickname = $this->input->post('nickname');
		$realname = $this->input->post('realname');
		$identificationNumber = $this->input->post('identificationNumber');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$pwd = $this->input->post('newpwd');
		$nation = $this->input->post('nation');
		$level = $this->input->post('level');
		if($level == 1){
			//志愿者
			$status = 1;
			$activation = $this->input->post('activation') == '' ? 0 : 1;
		}else if($level == 2 || $level == 0){
			//媒体 //普通会员
			$activation = 1;
			$status = $this->input->post('status') == '' ? 0 : 1;
		}
		$integral = $this->input->post('integral');
		$birthAddress = $this->input->post('birthAddress');
		$liveAddress = $this->input->post('liveAddress');
		$contactAddress = $this->input->post('contactAddress');
		$event = $this->input->post('event');
		$item = $this->input->post('item');
		$prize = $this->input->post('prize');
		$media = $this->input->post('media');
		$time = date('Y-m-d H:i:s');

		$data = array(
			'region' => '86',
			'nickname' => $nickname,
		    'realname' => $realname,
		    'identificationNumber' => $identificationNumber,
		    'phone' => $phone,
		    'email' => $email,
		    'nation' => $nation,
		    'level' => $level,
		    'integral' => $integral,
		    'birthAddress' => $birthAddress,
		    'liveAddress' => $liveAddress,
		    'contactAddress' => $contactAddress,
		    'mediaCompany' => $media,
			'sportEvent' => $event,
			'sportTeam' => $item,
			'sportPrize' => $prize,
		    'activation' => $activation,
		    'status' => $status,
		    'createdAt' => $time
		);

		//头像文件存在判断
		if(!empty($_FILES["avatar"]["name"]) && is_uploaded_file($_FILES["avatar"]["tmp_name"])){

			//重命名
			$attchname = $_FILES["avatar"]["name"];
			$ext = substr($attchname,strripos($attchname,'.') + 1);
			$name = date('Ymd').rand(0,999).'.'.$ext;
			$config['file_name'] = $name;

			$config['upload_path'] = 'upload/member/avatar/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			//$config['max_size'] = '0';
			//$config['max_width'] = '1024';
			//$config['max_height'] = '768';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload('avatar')){
	            $error = array('error' => $this->upload->display_errors());
	            print_r($error);
	        }else{
	            $info = $this->upload->data();
	            if($info && !empty($id)){//删除旧图
					$query = $this->db->query("SELECT portraitUri FROM users WHERE id={$id}");
		    		$row = $query->row(); 
					@unlink($row->portraitUri);
			    }
			    $data['portraitUri'] = base_url().$config['upload_path'].$name;
	        }
		   
		}

		if(!empty($pwd)){
			$rand = rand(1000,9999);
			//$data['pwd'] = password_hash($pwd, PASSWORD_BCRYPT);
			$data['passwordHash'] = sha1($pwd.'|'.$rand);
			$data['passwordSalt'] = $rand;
		}
		
		if(empty($id)){
			$query = $this->db->query("SELECT * FROM users WHERE realname='{$realname}' OR phone='{$phone}'");
	    	$row = $query->row(); 
			if($row){
				jump('该信息已存在',site_url('member/add'));
			}
			$bool = $this->db->insert('users', $data);

			//增加志愿者相关添加即时通讯TOKEN
			if($level == 1){

			 	$id = $this->db->insert_id();

			 	include '/API/rongcloud/WebIm.php';

				$rongcloud = new WebIm();

			 	$token = $rongcloud->getImToken($id,$realname,$data['portraitUri']);

			 	$data['rongCloudToken'] = $token;

			 	$this->db->where('id', $id);
			 	$this->db->update('users', $data);

			}

		}else{
			$this->db->where('id', $id);
			$bool = $this->db->update('users', $data);
		}
		
		if($bool) {
            jump('操作成功',site_url('member/index'));
        }else{
            jump('操作失败');
        }

	}

	public function del($id = null){

		$pid = $this->input->post('id');

		$gid = $id;

		$bool = false;

		if($pid){//多删

			//删除相关 ?
			$this->quitGroups($pid);

			$bool = $this->db->query("DELETE FROM users WHERE id IN(".implode(',', $pid).")"); 

		}else if (!empty($gid)) {//单删

			$this->quitGroups(array($gid));

			$bool = $this->db->delete('users', array('id' => $gid));

		}
		
		if($bool) {
            jump('操作成功',site_url('member/index'));
        }else{
            jump('操作失败');
        }

	}

	/*
	 * quitGroups 退出用户所在的群组
	 * @param 	array 	$mids 用户ID
	 */
	public function quitGroups($mids){

		if(is_array($mids)){

			include '/API/rongcloud/WebIm.php';

			$rongcloud = new WebIm();

			$num = count($mids);

			for ($i=0; $i < $num; $i++) { 

				$que = $this->db->query("SELECT * FROM users WHERE id={$mids[$i]} AND level=1");
				$res = $que->row();

				//志愿者
				if($res){

					$sql = "
						SELECT
							g.id,
							g.name
						FROM
							groups g
						INNER JOIN
							group_members m
						ON
							g.id=m.groupId
						WHERE
							m.memberId={$mids[$i]}
							AND
							m.isDeleted=0
					";

					$query = $this->db->query($sql);

					$result = $query->result_array();

					if($result){

						foreach ($result as $key => $val) {
							$rongcloud->kickGroup($mids[$i],$val->name);
						}

					}

				}

			}

		}else{
			echo '必须得是用户数组';
		}

	}

}
?>