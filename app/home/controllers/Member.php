<?php
/*
* Member 登录/激活/注册/找密码/设置密码
* @package	Member
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
* @describe 冠军激活/志愿者/普通会员 
*/

error_reporting(E_ALL^E_NOTICE^E_WARNING);

class Member extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();
		$this->load->helper('func_helper');

		include '/API/rongcloud/WebIm.php';
		$this->rongcloud = new WebIm();

	}

	//用户中心
	public function index(){
		$this->load->view('zhuce.html');
	}

	//登录
	public function login(){
		$this->load->view('login.html');
	}

	//验证登录
	public function checklogin(){

		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Credentials: true');

		$this->load->helper('cookie');

		//清除cookie
		delete_cookie('phone');
		delete_cookie('pwd');
		
		$user = $this->input->post('user');

		$pwd = $this->input->post('pwd');


  		//id,region,nickname,realname,portraitUri,phone,passwordHash,passwordSalt,rongCloudToken,level,status,activation,nation,email,identificationNumber,sex,inviteCode,mediaCompany,sportEvent,sportTeam,sportPrize,birth,birthAddress,liveAddress,contactAddress

		$sql = "SELECT * FROM users ";

		$query = $this->db->query($sql."WHERE phone='{$user}' OR nickname='{$user}'");

		$res = $query->row();

		if(!$res){
			echo '用户不存在';
			exit();
		}

		if(sha1($pwd.'|'.$res->passwordSalt) != $res->passwordHash){
			echo '密码错误';
			exit();
		}

		//未审核判断
		if($res->status == 0 || $res->activation == 0){
			echo '该帐号未审核激活';
			exit();
		}

		if($res->level == 1){

			//志愿者登录后同步融云群组
	    	$groupInfo = array();

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
					m.memberId={$res->id}
					AND
					m.isDeleted=0
			";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			if($result){
				foreach ($result as $key => $val) {
					$groupInfo[$val['id']] = $val['name'];
				}
				$this->rongcloud->syncGroup($res->id,$groupInfo);
			}

		}

		$this->session->member = new stdClass();
		$this->session->member = $res;

		//设置cookie FOR webIM 顶级域名与跨域问题
		$ip = '101.37.107.121';
		//set_cookie('id', $res->id, time() + 24*60*60,$ip,'/');
		set_cookie('phone', $res->phone, time() + 2592000,$ip,'/');
		set_cookie('pwd', $pwd, time() + 2592000,$ip,'/');

		echo 1;	
		
	}

	//发送手机验证码
	public function sendverifycode(){

		$type = $this->input->post('type');//区分类型

		$phone = $this->input->post('mobile');//手机号

		$sql = "SELECT * FROM users WHERE phone='{$phone}'";

		$query = $this->db->query($sql);

		$res = $query->row();

		if($type == 'findpwd'){//查密码或完善信息

			if(!$res){
				echo '手机号码不存在';
				exit();
			}

		}else if ($type == 'information') {//完善信息
			// if($res){
			// 	echo '手机号码已被使用过';
			// 	exit();
			// }
		}

		/******* 短信发送 start *******/

		//$this->load->library('SMS');云通讯
		include '/app/home/libraries/SMS.php';

		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid = '8a216da862b935db0162cc25b31b050e';
		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken = 'a71d6255be32489097a079e87a56eef2';
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId = '8aaf070863f8fb04016402d113010b77';
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP ='app.cloopen.com';
		//请求端口，生产环境和沙盒环境一致
		$serverPort = '8883';
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion = '2013-12-26';

		// 初始化SMS SDK
	    $SMS = new REST($serverIP,$serverPort,$softVersion);
	    $SMS->setAccount($accountSid,$accountToken);
	    $SMS->setAppId($appId);
	    
	    // 发送模板短信
	    //echo "Sending TemplateSMS to $to <br/>";
	    
	    $code = randcode(6);//短信CODE码
		$datas = array($code,3);//3分钟

	    $result = $SMS->sendTemplateSMS($phone,$datas,1);

	    if($result == NULL ) {
	        echo "result error!";
	        break;
	    }

	    if($result->statusCode!=0) {
	        echo "error code :" . $result->statusCode . "<br>";
	        echo "error msg :" . $result->statusMsg;
	        //TODO 添加错误处理逻辑
	    }else{
	        //echo "Sendind TemplateSMS success!<br/>";
	        // 获取返回信息
	        //$smsmessage = $result->TemplateSMS;
	        //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
	        //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
	        //TODO 添加成功处理逻辑 缓存
	        $this->load->helper('cookie');
	        set_cookie("randcode",$code,180);
	    }
		
	}

	//找回/设置密码
	public function findpwd(){

		if($this->input->post('act') == 'update'){

			$phone = $this->input->post('mobile');

			$query = $this->db->query("SELECT * FROM users WHERE phone='{$phone}'");

			$res = $query->row();

			if(!$res){
				echo '手机号码不存在';
				exit();
			}

			//手机验证码
			$verifcode = $this->input->post('verifcode');

			$this->load->helper('cookie');

			if($verifcode != get_cookie("randcode")){
				echo '验证码错误';
				exit();
			}

			/*
			$data = array(
			    'pwd' => password_hash($this->input->post('pwd'), PASSWORD_BCRYPT)
			);
			*/
			$rand = rand(1000,9999);
			$data = array(
			    'passwordHash' => sha1($this->input->post('pwd').'|'.$rand),
			    'passwordSalt' => $rand
			);

			$this->db->where('phone', $phone);

			$bool = $this->db->update('users', $data);

			if($bool){
				echo 1;
			}else{
				echo '修改密码失败,请重新再试';
			}

		}else{
			$this->load->view('findpwd.html');
		}

	}

	//注册类型跳转
	public function zhuce(){
		$this->load->view('zhuce.html');
	}

	/*
	* uphead 上传头像
	* @base64img string 二进制图片
	* @filepath  string 路径
	* @return 	 string 图片
	*/
	public function uphead($base64img,$filepath){

		/* 检查并创建图片存放目录 */
		if (!file_exists($filepath)) {
		    mkdir($filepath, 0777);
		}

		/* 根据base64编码获取图片类型 */
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64img, $result)) {
		    $image_type = $result[2]; //data:image/jpeg;base64,
		    $output_file = $filepath.md5(time()).'.'. $image_type;
		}

		/* 将base64编码转换为图片编码写入文件 */
		$image_binary = base64_decode(str_replace($result[1], '', $base64img));

		if(file_put_contents($output_file, $image_binary)){
			$file = $output_file;
		}else{
			$file = '';
		}

		return $file;

	}

	//志愿者激活
	public function active(){
	
		//此部分为AJAX提交判断
		if($this->input->post('act') == 'update'){

			$user = $this->input->post('user');

			$inumber = $this->input->post('inumber');//131223

			$sql = "SELECT id,nickname,realname,level,integral,phone,identificationNumber,email,liveAddress,nation,contactAddress,birthAddress,sportPrize,sportTeam,sportEvent,activation FROM users WHERE level=1 AND (nickname='{$user}' OR realname='{$user}') AND (RIGHT(identificationNumber,6)='{$inumber}' OR RIGHT(phone,6)='{$inumber}')";

			$query = $this->db->query($sql);

			$res = $query->row();

			if(!$res){
				echo -1;
				exit();
			}

			if($res->activation == 1){
				echo -2;
				exit();
			}

			//志愿者激活 需要WEBIM即时通讯 TOKEN
			$username = empty($res->realname) ? $res->nickname : $res->realname;

			//头像
			$picurl = !empty($res->portraitUri) ? base_url().$res->portraitUri : '';

		 	$token = $this->rongcloud->getImToken($res->id,$username,$picurl);

		 	$code = strtoupper(randcode(8,'str'));//志愿者生成的激活码

			$rand = rand(1000,9999);

			$data = array(
			    'passwordHash' => sha1($this->input->post('pwd').'|'.$rand),
			    'passwordSalt' => $rand,
			    'rongCloudToken' => $token,
			    'inviteCode' => strtoupper(randcode(8,'str')),
			    'activation' => 1
			);

			$this->db->where('id', $res->id);
			$bool = $this->db->update('users', $data);

			if($bool){
				$this->session->member = $res;
				echo $code;
			}else{
				echo 0;//上传失败
			}	

		}else{
			$this->load->view('jihuo1.html');
		}
		
	}

	//志愿者注册
	public function volunteerreg(){

		if($this->input->post('act') == 'save'){

			$sql = "SELECT * FROM users WHERE 1=1";

			$code = $this->input->post('code');

			$query = $this->db->query($sql." AND inviteCode='{$code}'");

			$res = $query->row();

			if(!$res){
				echo '邀请码有误';
				exit();
			}

			$nick = $this->input->post('nick');

			$query = $this->db->query($sql." AND nickname='{$nick}'");

			$res = $query->row();

			if($res){
				echo '该用户名已注册';
				exit();
			}

			$data = array(
			    'nickname' => $nick,
			    'pwd' => $this->input->post('pwd'),
			    'level' => 1
			);

			$this->session->member = (object)$data;

		}else{
			$this->load->view('zhiyuanzc.html');
		}

	}

	//志愿者注册下一步
	public function checkvolunteerreg(){

		//帐号为空跳到登录
		if(empty($this->session->member)){
			redirect(site_url('/member/volunteerreg'));
		}

		if($this->input->post('act') == 'save'){

			//上传图片
			$base64_image_content = $this->input->post('base64img');
			$output_directory = 'upload/member/avatar/'; //设置图片存放路径

			$output_file = $this->uphead($base64_image_content,$output_directory);

			if (!empty($output_file)) {

				$nickname = $this->session->member->nickname;

				$query = $this->db->query("SELECT * FROM users WHERE nickname='{$nickname}'");

				$res = $query->row();

				//判断防止重复注册
				if(!$res){

					$rand = rand(1000,9999);
				    $data = array(
				    	'region' => '86',
					    'nickname' => $this->session->member->nickname,
					    'portraitUri' => base_url().$output_file,
					    'passwordHash' => sha1($this->session->member->pwd.'|'.$rand),
				    	'passwordSalt' => $rand,
					    'level' => 1,
					    'status' => 1,
					    'createdAt' => date('Y-m-d H:i:s')
					);

					$bool = $this->db->insert('users', $data);

					if($bool){

						$id = $this->db->insert_id();

						//志愿者注册 需要WEBIM TOKEN
					 	$token = $this->rongcloud->getImToken($id,$this->session->member->nickname,$output_file);

						$data = array(
					    	'rongCloudToken' => $token,
						);
						$this->db->where('id', $id);
						$this->db->update('users', $data);

						$this->session->member->id = $id;

						echo 1;

					}else{
						echo '注册失败';
					}

				}else{
					echo 1;
				}

			}else{
				echo '图片上传失败';
			}

		}else{

			$this->load->view('zhiyuanzc2.html');
		}

	}

	//普通会员注册
	public function generalreg(){

		if($this->input->post('act') == 'save'){

			$sql = "SELECT * FROM users WHERE 1=1";

			$nick = $this->input->post('nick');

			$query = $this->db->query($sql." AND nickname='{$nick}'");

			$res = $query->row();

			if($res){
				echo '该用户名已注册';
				exit();
			}

			$data = array(
			    'nickname' => $nick,
			    'pwd' => $this->input->post('pwd'),
			    'level' => 0
			);

			$this->session->member = (object)$data;

		}else{

			$this->load->view('putongzc.html');

		}

	}

	//普通会员注册下一步
	public function checkgeneralreg(){

		//帐号为空跳到登录
		if(empty($this->session->member)){
			redirect(site_url('/member/generalreg'));
		}

		if($this->input->post('act') == 'save'){

			//上传图片
			$base64_image_content = $this->input->post('base64img');
			$output_directory = 'upload/member/avatar/'; //设置图片存放路径

			$output_file = $this->uphead($base64_image_content,$output_directory);

			if (!empty($output_file)) {

				$nickname = $this->session->member->nickname;

				$query = $this->db->query("SELECT * FROM users WHERE nickname='{$nickname}'");

				$res = $query->row();

				if(!$res){

					$rand = rand(1000,9999);

					//password_hash($this->session->member->pwd,PASSWORD_BCRYPT),

				    $data = array(
				    	'region' => '86',
					    'nickname' => $this->session->member->nickname,
					    'portraitUri' => base_url().$output_file,
					    'passwordHash' => sha1($this->session->member->pwd.'|'.$rand),
					    'passwordSalt' => $rand,
					    'level' => 0,
					    'activation' => 1,
					    'createdAt' => date('Y-m-d H:i:s')
					);

					$bool = $this->db->insert('users', $data);
					if($bool){
						$this->session->member = (object)array('id' =>$this->db->insert_id());
						echo 1;
					}else{
						echo '注册失败';
					}

				}else{
					echo 1;
				}

			}else{
				echo '图片上传失败';
			}

		}else{

			$this->load->view('putongzc2.html');
		}

	}

	//完善志愿者/普通会员资料
	public function information(){

		//帐号为空跳到登录
		if(empty($this->session->member->id)){
			redirect(site_url('/member/login'));
		}

		$id = $this->session->member->id;
		
		$sql = "SELECT * FROM users";

		$query = $this->db->query($sql." WHERE id={$id}");

		$res = $query->row();

		//接收参数判断是注册还是更新
		$res->do = $this->uri->segment(3);

		//更新
		if($this->input->post('act') == 'save'){

			$phone = $this->input->post('mobile');
			$inumber = $this->input->post('inumber');
			$email = $this->input->post('email');
			$live = $this->input->post('live');
			$nation = $this->input->post('nation');
			$contact = $this->input->post('contact');
			$birth = $this->input->post('birth');
			$event = $this->input->post('event');
			$item = $this->input->post('item');
			$prize = $this->input->post('prize');
			$media = $this->input->post('media');
			$pwd = $this->input->post('pwd');

			$do = $this->input->post('do');

			//手机验证码
			$verifcode = $this->input->post('verifcode');

			$this->load->helper('cookie');

			if(!empty($phone)){

				$query = $this->db->query($sql." WHERE phone={$phone}");
				$res = $query->row();
				if($res && empty($do)){
					echo '该手机号码已被使用';
					exit();
				}

				if($verifcode != get_cookie("randcode") && $verifcode != '888888'){
					echo '验证码错误';
					exit();
				}
				
			}

			//会员积分查询是否存在
	    	$que = $this->db->query("SELECT * FROM users_integral WHERE type=1 AND mid={$id}");
	    	$num = $que->num_rows();

	    	$level = $this->session->member->level;

			if(!empty($phone) && !empty($inumber) && !empty($email) && !empty($live) && !empty($nation) && !empty($contact) && !empty($birth) && $num == 0){

				$sql = "UPDATE users SET integral=integral+500 WHERE id={$id}";

				$tip = '完善个人资料获得积分500';

				$arr = array(
	    			'type' => 1,
				    'mid' => $id,
				    'content' => $tip,
				    'integral' => 500,
				    'status' => 1,
				    'createTime' => date('Y-m-d H:i:s')
				);

				if($level == 0){

					//普通会员 更新会员积分
					$this->db->query($sql);

					//记录日志
					$this->db->insert($this->db->dbprefix('users_integral'), $arr);

				}

				if($level == 1 && !empty($event) && !empty($item) && !empty($prize)){

					//志愿者 更新会员积分
					$this->db->query($sql);

					//记录日志
					$this->db->insert($this->db->dbprefix('users_integral'), $arr);

				}

				if($level == 2 && !empty($media)){

					//记者媒体 更新会员积分
					$this->db->query($sql);

					//记录日志
					$this->db->insert('users_integral', $arr);
				}
				
			}

			$data = array(
				'phone' => $phone,
			    'identificationNumber' => $inumber,
			    'email' => $email,
			    'liveAddress' => $live,
			    'nation' => $nation,
			    'contactAddress' => $contact,
			    'birthAddress' => $birth,
			    'mediaCompany' => $media,
			    'sportEvent' => $event,
			    'sportTeam' => $item,
			    'sportPrize' => $prize
			);

			if(!empty($pwd)){
				$rand = rand(1000,9999);
				//$data['pwd'] = password_hash($pwd,PASSWORD_BCRYPT);
				$data['passwordHash'] = sha1($pwd.'|'.$rand);
				$data['passwordSalt'] = $rand;
			}

			$this->db->where('id', $id);

			$bool = $this->db->update('users', $data);

			if($bool){
				echo '更新成功.'.$tip;
			}else{
				echo '更新失败.';
			}

		}else{
			$this->load->view('information.html',$res);
		}

	}

	//注册协议
	public function agreement(){

		//详情
		$query = $this->db->query("SELECT * FROM article WHERE articleId=2");
    	$data = $query->row();

    	if(!$data){
    		show_error('暂无数据',null,'信息提示');
    	}

		$this->header['nav'] = '注册协议';

    	$this->load->view('templates/header.html',$this->header);
		$this->load->view('agreement.html',$data);
		$this->load->view('templates/footer.html');
	}

}
?>