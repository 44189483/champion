<?php
/*
* User 会员中心相关
* @package	User
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class User extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url_helper');
		$this->load->database();
		$this->load->helper('func_helper');

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
	
	//会员首页
	public function index(){

		$this->isMemberLogin();

		//统计好友数量
		$query = $this->db->query("SELECT * FROM friendships WHERE userId={$this->session->member->id}");
	    $data['friendtotal'] = $query->num_rows();

	    //参加活动数量
		$query = $this->db->query("SELECT id FROM signup WHERE mid={$this->session->member->id}");
	    $data['acttotal'] = $query->num_rows();

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/index.html',$data);
		$this->load->view('templates/footer.html');

	}

	//会员资料
	public function information(){

		$this->isMemberLogin();

		$id = $this->session->member->id;
		
		$sql = "SELECT * FROM users WHERE id={$id}";

		$query = $this->db->query($sql);

		$res = $query->row();

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/information.html',$res);

	}

	//更新头像
	public function avatar(){

		$this->isMemberLogin();

		if($this->input->post('act') == 'save'){

			$id = $this->session->member->id;

			//上传图片
			$base64_image_content = $this->input->post('base64img');
			$output_directory = 'upload/member/avatar/'; //设置图片存放路径

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

				if(!empty($id)){//删除旧图
					$query = $this->db->query("SELECT portraitUri FROM users WHERE id={$id}");
		    		$row = $query->row();

		    		$filepath = str_replace(base_url(),"/",$row->portraitUri);

		    		//替换域名IP
					@unlink($_SERVER['DOCUMENT_ROOT'].$filepath);
			    }

			    $data = array(
				    'portraitUri' => base_url().$output_file
				);

				$this->db->where('id', $id);

				$bool = $this->db->update('users', $data);

				//更新SESSION头像
				$this->session->member->portraitUri = $data['portraitUri'];

				if($bool){
					echo 1;
				}else{
					echo 0;//图片上传失败
				}

			}else{
				echo '图片上传失败';
			}

		}else{

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('user/avatar.html');

		}

	}

	//我的收藏
	public function collect(){

		$this->isMemberLogin();

		$type = $this->uri->segment(3) == null ? 'activity' : $this->uri->segment(3);

		$file = $this->uri->segment(4);

		$per_page = 5; //每页显示的数据数

		//会员发表相关
		$sql = "
			SELECT 
				p.* 
			FROM 
				users_collect c
			";

		switch ($type) {

			case 'activity':

				//活动
				$sql .= "
					INNER JOIN
						activity p
					ON
						c.aid=p.id
					WHERE 
						c.mid={$this->session->member->id} 
						AND 
						c.type=2
						ORDER BY c.id DESC
					";

					$now = date('Y-m-d H:i:s');

					if($file == 'ajax'){

						//ajax 无刷新列表
						$current_page = intval($this->input->get('page')); //获取当前分页页码数
				    
				    	$offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

						$sql .= " LIMIT {$offset},{$per_page}";

						$query = $this->db->query($sql);
					    $data = $query->result();

					    foreach ($data as $key => $val) {

		    				$data[$key]->title = strip_tags(utfSubstr($val->title, 0, 25));

		    				$data[$key]->startDate = substr($val->startTime, 0, 10);

		    				$data[$key]->endDate = substr($val->endTime, 0, 10);

					    	if($this->session->member->id > 0){
						    	$que = $this->db->query("SELECT id FROM signup WHERE aid={$val->id} AND mid={$this->session->member->id}");
						    	$res = $que->row();	
					    	}
					    	$data[$key]->isSign = $res == true ? 1 : 0;

							//活动状态判断
					    	if($val->startTime > $now){//正在招募
					    		$data[$key]->status = 'ready';
					    	}else if($val->endTime < $now){//已结束
					    		$data[$key]->status = 'over';
					    	}else if($val->startTime <= $now && $val->endTime >= $now){
					    		$data[$key]->status = 'begain';
					    	}

		    			}

					    if($data){
					    	echo $json = json_encode($data);
					    }

					    exit();

					}else{
						
						$query = $this->db->query($sql);
				   		$data['total'] = $query->num_rows();
				    
					}

				break;

			case 'pic':
				
				//图集
				$sql .= "
					INNER JOIN
						users_post p
					ON
						c.aid=p.id
					WHERE 
						c.mid={$this->session->member->id} 
						AND 
						c.type=1 
						AND 
						p.type=2
						ORDER BY c.id DESC
					";

					if($file == 'ajax'){

						//ajax 无刷新列表
						$current_page = intval($this->input->get('page')); //获取当前分页页码数
				    
				    	$offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

						$sql .= " LIMIT {$offset},{$per_page}";

						$query = $this->db->query($sql);
					    $data = $query->result();

					    foreach ($data as $key => $val) {
					    	
					    	$que = $this->db->query("SELECT nickname,portraitUri FROM users WHERE id={$val->mid}");
					    	$res = $que->row();
					    	$data[$key]->author = $res->nickname;
					    	$data[$key]->head = $res->portraitUri;

		    				$data[$key]->image = $val->content;
		    				unset($data[$key]->content);
		    				$data[$key]->date = substr($val->createTime, 0,10);
		    				unset($data[$key]->createTime);

		    				//判断当前会员是否点过赞
				    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
				    		$zan_num = $zan_query->num_rows();

				    		$data[$key]->isPraise = $zan_num > 0 ? 1 : 0;

		    			}

					    if($data){
					    	echo $json = json_encode($data);
					    }else{
					    	echo 0;
					    }

					    exit();

					}else{

						$sql .= " LIMIT 0,{$per_page}";
						
						$query = $this->db->query($sql);
				   		$data['result'] = $query->result();
				   		
		    			foreach ($data['result'] as $key => $val) {
		    				
					    	$que = $this->db->query("SELECT nickname,portraitUri FROM users WHERE id={$val->mid}");
					    	$res = $que->row();
					    	
					    	//获取发布作者
							if($val->mid == 0){
								$data['result'][$key]->author = '官方';
								$data['result'][$key]->head = '/public/home/images/logo.png';
							}else{
								$que = $this->db->query("SELECT nickname AS author,portraitUri FROM users WHERE id={$val->mid}");
								$res = $que->row();
								$data['result'][$key]->head = $res->portraitUri;
								$data['result'][$key]->author = $res->author;
							}

		    				$data['result'][$key]->image = $val->content;
		    				unset($data['result'][$key]->content);
		    				$data['result'][$key]->date = substr($val->createTime, 0,10);
		    				unset($data['result'][$key]->createTime);

		    				//判断当前会员是否点过赞
				    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
				    		$zan_num = $zan_query->num_rows();

				    		$data['result'][$key]->isPraise = $zan_num > 0 ? 1 : 0;

		    			}
				    
					}
				break;

			case 'video':

				//视频
				$sql .= "
					INNER JOIN
						users_post p
					ON
						c.aid=p.id
					WHERE 
						c.mid={$this->session->member->id} 
						AND 
						c.type=1 
						AND 
						p.type=3
						ORDER BY c.id DESC
					";
				
				if($file == 'ajax'){

					//ajax 无刷新列表
					$current_page = intval($this->input->get('page')); //获取当前分页页码数
			    
			    	$offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

					$sql .= " LIMIT {$offset},{$per_page}";

					$query = $this->db->query($sql);
				    $data = $query->result();
				    foreach ($data as $key => $val) {
				    	$que = $this->db->query("SELECT nickname,portraitUri FROM users WHERE id={$val->mid}");
				    	$res = $que->row();
				    	$data[$key]->author = $res->nickname;
				    	$data[$key]->head = $res->portraitUri;

	    				$data[$key]->date = substr($val->createTime, 0,10);
	    				unset($data[$key]->createTime);

	    				//判断当前会员是否点过赞
			    		$zan_query = $this->db->query("SELECT * FROM praise WHERE type=2 AND cid={$val->id} AND mid={$this->session->member->id}");
			    		$zan_num = $zan_query->num_rows();

			    		$data[$key]->isPraise = $zan_num > 0 ? 1 : 0;

	    			}

				    if($data){
				    	echo $json = json_encode($data);
				    }

				    exit();

				}else{

					$query = $this->db->query($sql);
				   	$data['total'] = $query->num_rows();
			    
				}

				break;

			case 'article':

				//美文
				$sql .= "
					INNER JOIN
						users_post p
					ON
						c.aid=p.id
					WHERE 
						c.mid={$this->session->member->id} 
						AND 
						c.type=1 
						AND 
						p.type=1
						ORDER BY c.id DESC
					";
				
				if($file == 'ajax'){

					//ajax 无刷新列表
					$current_page = intval($this->input->get('page')); //获取当前分页页码数
			    
			    	$offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

					$sql .= " LIMIT {$offset},{$per_page}";

					$query = $this->db->query($sql);
				    $data = $query->result();

				    foreach ($data as $key => $val) {
	    				$data[$key]->content = utfSubstr(strip_tags($val->content), 0, 100);
	    				$data[$key]->date = substr($val->createTime, 0,10);
	    				unset($data[$key]->createTime);
	    			}

				    if($data){
				    	echo $json = json_encode($data);
				    }

				    exit();

				}else{

					$query = $this->db->query($sql);
				   	$data['total'] = $query->num_rows();
			    
				}

				break;
			
		}

		$data['type'] = $type;

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/collect.html',$data);
		$this->load->view('templates/footer.html');

	}

	//我的公益
	public function activity($type = null){

		$this->isMemberLogin();

		$file = $this->input->get('file');

		$now = date('Y-m-d H:i:s');

		$sql = "
			SELECT 
				a.id,
				a.title,
				a.pic,
				a.integral,
				SUBSTRING(startTime,1,10) AS startDate,
				SUBSTRING(endTime,1,10) AS endDate 
			FROM 
				signup s 
			INNER JOIN
				activity a
			ON
				s.aid=a.id
			WHERE 
				a.status=1
				AND
				s.mid={$this->session->member->id}
		";

		if($type == 'begain'){//进行中
			$sql .= " AND (a.startTime <= '{$now}' AND a.endTime >= '{$now}')";
		}else if($type == 'over'){//已结束
			$sql .= " AND a.endTime < '{$now}'";
		}else{//正在招募
			$sql .= " AND a.startTime > '{$now}'";
		}

		$sql .= " ORDER BY a.endTime DESC,a.id DESC";

		//无刷新加载列表
		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

		    $sql .= " LIMIT {$offset},{$per_page}";

		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->title = strip_tags(utfSubstr($val->title, 0, 25));
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
			$this->load->view('user/activity.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//荣誉证书
	public function honor(){

		$this->isMemberLogin();

		$file = $this->input->get('file');

		$now = date('Y-m-d H:i:s');

		$sql = "
			SELECT 
				a.id,
				a.title,
				DATE_FORMAT(SUBSTRING(endTime,1,10),'%Y年%m月%d日') AS endDate
			FROM 
				signup s 
			INNER JOIN
				activity a
			ON
				s.aid=a.id
			WHERE 
				s.mid={$this->session->member->id}
				AND
				a.endTime < '{$now}'
				AND
				s.status=1
		";

		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

			$sql .= " LIMIT {$offset},{$per_page}";

			$query = $this->db->query($sql);
		    $result = $query->result();

		    if($result){
		    	echo $json = json_encode($result);
		    }

		}else{

		    $query = $this->db->query($sql);
		    $total = $query->num_rows();
		    
		    $data = array(
		    	'total' => $total
		    );

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('user/honor.html',$data);
			//$this->load->view('templates/footer.html');
		
		}

	}

	//志愿证
	public function certificate(){

		$this->isMemberLogin();

		$filename = "./upload/member/qrcode/{$this->session->member->id}.png";

		//生成志愿者图片
		$this->qrcode();
		
		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/certificate.html');

	}

	//生成带志愿者证的二维码
   	public function qrcode(){

   		$this->isMemberLogin();

    	$this->load->library('Phpqrcode');

       	$id = $this->session->member->id;

       	//会员头像
       	$avatar = str_replace(base_url(),"./",$this->session->member->portraitUri);

       	if(!empty($avatar)){

	       	//生成二维码地址
	       	$qrcode = "./upload/member/qrcode/{$id}.png";

	       	//背景线条
	       	$line = './upload/member/bg_line.png';

	       	//背景图片
	       	$bg = './upload/member/bg.png';

			//生成带有背景的二维码
			
	        $object = new QRcode();
	        
	        //二维码网址
	        $url = base_url()."user/history/{$id}";

	        //容错级别
	        $errorCorrectionLevel = 'L';
	        //生成图片大小
	        $matrixPointSize = 10;
	        //生成一个二维码图片
	        $object->png($url,$qrcode, $errorCorrectionLevel, $matrixPointSize, 2);
	        
	        //背景图片存在
	        if (file_exists($bg)) {

	            $bg = imagecreatefromstring(file_get_contents($bg));
	            //if (imageistruecolor($bg)){
	            	//添加这行代码来解决颜色失真问题
	            	//imagetruecolortopalette($bg, false, 65535);
	            //}
	            $bg_width = imagesx($bg);   //背景图片宽度
	            $bg_height = imagesy($bg);  //背景图片高度
	            
	            //设置一个颜色变量为白色
	            $white = imagecolorallocate($bg, 255, 255, 255);

	            $from_text = ($bg_width - 100) /2; 

	            imagettftext($bg, 24, 0, $from_text, 100, $white, "./public/home/font/simhei.ttf", '志愿者');

	            //姓名
	            $realname = $this->session->member->realname;
	            $w1 = text_width($realname,20);
	            $w = ($bg_width - $w1) /2;
	            imagettftext($bg, 20, 0, $w, 500, $white, "./public/home/font/simhei.ttf", $realname);
	            
	            //匿称
	            $nickname = $this->session->member->nickname;
	            $w2 = text_width($nickname,16);
	            $w = ($bg_width - $w2) /2;
	            imagettftext($bg, 16, 0, $w, 530, $white, "./public/home/font/simhei.ttf", $nickname);

	            //组合背景+头像图片
	            if (file_exists($avatar)) {
	            	$avatar = imagecreatefromstring(file_get_contents($avatar));
		            $avatar_width = imagesx($avatar);       //头像图片宽度
		            $avatar_height = imagesy($avatar);      //头像图片高度
		            $avatar_qr_width = $bg_width / 2;
		            $scale = $avatar_width / $avatar_qr_width;
		            $avatar_qr_height = $avatar_height / $scale;
		            $from_width = ($bg_width - $avatar_qr_width) / 2;
		            $from_height = 150;
		            imagecopyresampled($bg, $avatar, $from_width, $from_height, 0, 0, $avatar_qr_width,
		                $avatar_qr_height, $avatar_width, $avatar_height);
	        	}

	            //组合背景+二维码图片
	            if (file_exists($qrcode)) {
	            	$qrcode = imagecreatefromstring(file_get_contents($qrcode));
		            $qrcode_width = imagesx($qrcode);       //二维码图片宽度
		            $qrcode_height = imagesy($qrcode);      //二维码图片高度
		            $qrcode_qr_width = $bg_width / 4;
		            $scale = $qrcode_width / $qrcode_qr_width;
		            $qrcode_qr_height = $qrcode_height / $scale;
		            $from_width = ($bg_width - $qrcode_qr_width) / 2;
		            $from_height = 550;
		            imagecopyresampled($bg, $qrcode, $from_width, $from_height, 0, 0, $qrcode_qr_width,
		                $qrcode_qr_height, $qrcode_width, $qrcode_height);
	        	}

	        	//组合背景+线条
	            if (file_exists($line)) {
	            	$line = imagecreatefromstring(file_get_contents($line));
		            $line_width = imagesx($line);       //线条图片宽度
		            $line_height = imagesy($line);      //线条图片高度
		            $line_qr_width = $bg_width / 2;
		            $line_qr_height = $line_height;
		            $from_width = ($bg_width - $line_qr_width) / 2;
		            $from_height = 750;
		            imagecopyresampled($bg, $line, $from_width, $from_height, 0, 0, $line_qr_width,
		                $line_qr_height, $line_width, $line_height);
	        	}

	        	//设置一个颜色变量为灰色
	            $gray = imagecolorallocate($bg, 128,128,128);

	            //证件ID
	            imagettftext($bg, 20, 0, $from_text, 780, $gray, "./public/home/font/simhei.ttf", '证件ID:'.$this->session->member->id);

	        }

	        //删除二维码
	        //@unlink($_SERVER['DOCUMENT_ROOT']."/upload/member/qrcode/{$id}.png");

	        //输出图片
	        imagepng($bg, "./upload/member/qrcode/bgqr_{$id}.png");

    	}

    }

	//我的经历
	public function history($mid){

		$file = $this->input->get('file');

		//获取会员信息
		$que = $this->db->query("SELECT id,nickname,realname,portraitUri FROM users WHERE id={$mid}");
	    $res = $que->row();

		$now = date('Y-m-d H:i:s');

		$sql = "
			SELECT
				a.id,
				a.title,
				SUBSTRING(s.createTime,1,10) AS cdate 
			FROM 
				signup s 
			INNER JOIN
				activity a
			ON
				s.aid=a.id
			WHERE 
				a.status=1
				AND
				s.mid={$mid}
				AND 
				a.endTime < '{$now}'
		";

		//AJAX 无刷新列表
		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

		    $sql .= " LIMIT {$offset},{$per_page}";

		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->title = utfSubstr($val->title, 0, 25);
		    }
		    
		    if($result){
		    	echo $json = json_encode($result);
		    }

		}else{

		    $query = $this->db->query($sql);
		    $total = $query->num_rows();
		    	    
		    $data = array(
		    	'member' => $res,
		    	'total' => $total
		    );

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('user/history.html',$data);
			//$this->load->view('templates/footer.html');

		}

	}

	//志愿者服务证明
	public function prove($type = null){

		$this->isMemberLogin();

		$file = $this->input->get('file');

		$now = date('Y-m-d H:i:s');

		$sql = "
			SELECT 
				a.id,
				a.title,
				a.serviceNumber
			FROM 
				signup s 
			INNER JOIN
				activity a
			ON
				s.aid=a.id
			WHERE 
				s.status=1
				AND
				s.mid={$this->session->member->id}
		";

		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

			$sql .= " LIMIT {$offset},{$per_page}";
		    
		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->title = utfSubstr($val->title, 0, 30);
		    }
		    
		    if($result){
		    	echo $json = json_encode($result);
		    }

		}else{
	    
		    $query = $this->db->query($sql);
		    $total = $query->num_rows();
		    
		    $data = array(
		    	'total' => $total
		    );

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('user/prove.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	//志愿者服务证明详情
	public function provedetail($id){

		$this->isMemberLogin();

		$id = $this->uri->segment(3);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

		$sql = "
			SELECT 
				a.id,
				a.title,
				a.serviceNumber,
				a.sponsor,
				a.activityAddress,
				DATE_FORMAT(SUBSTRING(a.endTime,1,10),'%Y年%m月%d日') AS endDate,
				DATE_FORMAT(SUBSTRING(s.createTime,1,10),'%Y年%m月%d日') AS cdate
			FROM 
				signup s 
			INNER JOIN
				activity a
			ON
				s.aid=a.id
			WHERE
				a.id={$id}
				AND 
				s.status=1
				AND
				s.mid={$this->session->member->id}
		";
	    
	    $query = $this->db->query($sql);
	    $row = $query->row();

	    //生成图片
	    $img = "./upload/member/prove/{$id}_{$this->session->member->id}.png";
	    $row->hadimg = file_exists($img) ? 1 : 0;
	    
		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/provedetail.html',$row);

	}

	//生成志愿者服务证明图
   	public function provepic(){

   		//工作区域宽
		$width = $this->input->post('w');
		//工作区域高
   		$height = $this->input->post('h');

   		//传递内容
   		$post = $this->input->post('arr');

   		$id = $this->session->member->id;

   		//字体类型
		$font ="./public/home/font/simhei.ttf";

		$bg = @imagecreate($width, $height); // 创建画布

		$white = @imagecolorallocate($bg, 255, 255, 255); // 创建白色

		//字体大小
		$size = 12;	
		$fontWidth = imagefontwidth($size);  
	    //$fontHeight = imagefontheight($size);

		//设置字体颜色
		$gray = @imagecolorallocate($bg, 128,128,128);

		//将ttf文字写到图片中
		@imagettftext($bg, $size, 0, 15, 25, $gray, $font, $post[0]);

 		$content = "";

 		$letter = array();

	 	// 将字符串拆分成一个个单字 保存到数组 letter 中
		for($i = 0; $i < mb_strlen($post[1]); $i++) {
			$letter[] = mb_substr($post[1], $i, 1);
		}

		//自动换行处理
		foreach ($letter as $l) {
		  $teststr = $content." ".$l;
		  $testbox = @imagettfbbox($size, 0, $font, $teststr);
		  // 判断拼接后的字符串是否超过预设的宽度
		  if(($testbox[2] > $width * 0.96) && ($content !== "")) {
		  	$content .= "\n";
		  }
		  $content .= $l;
		}

		$w = $width*0.3;

		@imagettftext($bg, $size, 0, 15, 50, $gray, $font, $content);

		@imagettftext($bg, $size, 0, $w, $height*0.68, $gray, $font, $post[2]);

		//$textWidth = $fontWidth * mb_strlen($post[3]);  
	    //$x = ($width - $textWidth - 138) / 2;//计算文字的水平位置

		@imagettftext($bg, $size, 0, $w, $height*0.75, $gray, $font, $post[3]);

		@imagettftext($bg, $size, 0, $w, $height*0.85, $gray, $font, $post[4]);
	
		@imagettftext($bg, $size, 0, $w, $height*0.95, $gray, $font, $post[5]);

		//印章
		$sign = $post[6];
		if (file_exists($sign)) {
			$sign = @imagecreatefromstring(file_get_contents($sign));
	        $sign_width = imagesx($sign);       //图片宽度
	        $sign_height = imagesy($sign);      //图片高度
	        $from_width = $width - $sign_width - 10;
	        $from_height = $height - $sign_height - 10;
	        @imagecopyresampled($bg, $sign, $from_width, $from_height, 0, 0, $sign_width,$sign_height, $sign_width, $sign_height);
		}

        //输出图片
        @imagepng($bg, "./upload/member/prove/{$post[7]}_{$id}.png");

        //$ImgUrl = "./upload/member/prove/{$post[7]}_{$id}.png";

        //$this->ImageAddBoard($ImgUrl,'./upload/member/prove/');

    }

    //我的投稿
    public function contribute($type){

    	$this->isMemberLogin();

    	$type = $this->uri->segment(3);

    	$file = $this->input->get('file');

		if(empty($type)){
    		show_error('参数有误',null,'提示');
    	}

		if($type == 'zx'){
			//媒体资讯
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
					a.mid={$this->session->member->id} 
					AND a.type IN(1,2)
			";	
		}else if($type == 'zl'){
			//活动资料
			$now = date('Y-m-d H:i:s');
			$sql = "
				SELECT 
					id,
					title,
					pic,
					SUBSTRING(startTime,1,10) AS startDate,
					SUBSTRING(endTime,1,10) AS endDate 
				FROM 
					activity a 
				WHERE 
					id IN (SELECT aid FROM users_post WHERE mid={$this->session->member->id} GROUP BY aid)
			";
		}

		//AJAX 无刷新列表
		if($file == 'ajax'){

			$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

		    $sql .= " LIMIT {$offset},{$per_page}";
	    
		    $query = $this->db->query($sql);
		    $result = $query->result();
			foreach ($result as $key => $val) {
		    	if($type == 'zx'){
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

		    $query = $this->db->query($sql);
		    $total = $query->num_rows();
			
		    $data = array(
		    	'class' => $type,
		    	'total' => $total
		    );

		    $this->load->view('templates/header.html',$this->header);
			$this->load->view('user/contribute.html',$data);
			$this->load->view('templates/footer.html');

		}

    }

    //我的积分
	public function integral($mid){

		$this->isMemberLogin();

		$file = $this->input->get('file');

		$sql = "SELECT content,integral,SUBSTRING(createTime,1,10) AS createdate FROM users_integral WHERE mid={$mid} ORDER BY id DESC";

	    if($file == 'ajax'){

	    	$per_page = 5; //每页显示的数据数
		    $current_page = intval($this->input->get('page')); //获取当前分页页码数
		    
		    $offset = ($current_page - 1) * $per_page; //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）

		    $sql .= " LIMIT {$offset},{$per_page}";
		    
		    $query = $this->db->query($sql);
		    $result = $query->result();
		    foreach ($result as $key => $val) {
		    	$result[$key]->content = utfSubstr($val->content,0,25);
		   	}
		    if($result){
		    	echo $json = json_encode($result);
		    }

	    }else{

	    	$query = $this->db->query($sql);
		    $total = $query->num_rows();
		    $data = array(
		    	'total' => $total
		    );

			$this->load->view('templates/header.html',$this->header);
			$this->load->view('user/integral.html',$data);
			$this->load->view('templates/footer.html');

		}

	}

	public function getintegral(){

		$this->isMemberLogin();

		$query = $this->db->query("SELECT content FROM article WHERE type=3");
	    $data = $query->row();

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/integraldetail.html',$data);
		
	}
 
    //下载
    public function download(){ 

    	$type = $this->uri->segment(3);

    	$id = $this->uri->segment(4);

		if(empty($id) || !is_numeric($id)){
    		show_error('参数有误',null,'提示');
    	}

    	if($type == 'qr'){//二维码
    		$file_url = "./upload/member/qrcode/bgqr_{$id}.png";
    	}else if($type == 'pr'){//证明书
    		$file_url = "./upload/member/prove/{$id}_{$this->session->member->id}.png";
    	}

	    if(!file_exists($file_url)){
	      echo '文件不存在'; 
	    } 

	    $file_name = basename($file_url);  
	    $file_type = fopen($file_url,'r'); //打开文件

	    //输入文件标签 
	    header("Content-type: application/octet-stream"); 
	    header("Accept-Ranges: bytes"); 
	    header("Accept-Length: ".filesize($file_url)); 
	    header("Content-Disposition: attachment; filename=".$file_name); 

	    //输出文件内容 
	    echo fread($file_type,filesize($file_url)); 
	    fclose($file_type);

	}

	//用户设置页面
	public function setup(){

		$this->isMemberLogin();

		$this->load->view('templates/header.html',$this->header);
		$this->load->view('user/setup.html');//,$data
		$this->load->view('templates/footer.html');

	}

	//控制设备是否接收推送
	public function getPushId(){

		//推送ID
		$registration_id = $this->input->post("registration_id");

		//机型
		$type = $this->input->post("type");

		$query = $this->db->query("SELECT * FROM notice_push WHERE registration_id='{$registration_id}'");
    	$res = $query->row();

    	$data = array(
		    'registration_id' => $registration_id,
		    'type' => $type
		);

    	if(!$res){
    		//不存在增加记录		
			$this->db->insert('notice_push', $data);
    	}

    	$this->session->device = new stdClass();
		$this->session->device = $data;

    }

	//控制设备是否接收推送
	public function mobilePush(){

		//推送ID
		$registration_id = $this->session->device->registration_id;

		//推状态
    	$status = $this->input->post('status');

		$data = array(
    		'status' => $status
		);
		$this->db->where('registration_id', $registration_id);
		$this->db->update('notice_push', $data);

    }
	
	//退出
	public function logout(){
		$this->load->helper('cookie');
		delete_cookie('phone');//即时通讯缓存
		delete_cookie('pwd');//即时通讯缓存 
    	//退出 清除session
		$this->session->member = array();
		session_destroy();
		redirect(site_url('/member/login'));
    }

}
?>