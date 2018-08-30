<?php
/*
* RequstShare APP分享回传文件
* @package	RequstShare
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class RequstShare extends CI_Controller{

	public function __construct(){
		
		parent::__construct();
		$this->load->database();

	}

	//回传后更新记录
	public function info(){

		//$myfile = fopen("newfile.txt", "w+");
		$json = file_get_contents("php://input");
		//$txt = "json\n";
		//fwrite($myfile, $json);
		//fclose($myfile);


		//$json = '{"mid":"2","type":"5","id":"49","title":"优秀运动员助力健康中国行—科学健身主题宣传活动启动仪式"}';

		if(empty($json)){
			//失败
			echo '{"code":"400"}'; 
			exit();
		}

		$array = json_decode(htmlspecialchars_decode($json));
		
		$id = $array->id;//分享ID

		$type = $array->type;//类型

		$title = $array->title;//分享标题

		$mid = $array->mid;//会员ID

		//防止不断分享信息来刷新积分
		$query = $this->db->query("SELECT * FROM users_integral WHERE type={$type} AND cid={$id} AND mid={$mid}");
		$res = $query->row();

		if(!$res){

			$integral = 12;

			$sql = "UPDATE users SET integral=integral+{$integral} WHERE id={$mid}";

			$tip = '分享"'.$title.'"获得积分'.$integral;

			$data = array(
				'type' => $type,
			    'mid' => $mid,
			    'cid' => $id,
			    'content' => $tip,
			    'integral' => $integral,
			    'status' => 1,
			    'createTime' => date('Y-m-d H:i:s')
			);

			//记录日志
			$bool = $this->db->insert($this->db->dbprefix('users_integral'), $data);
			if($bool){
				//成功
				echo '{"code":"200"}';
			}else{
				//失败
				echo '{"code":"400"}';
			}

		}else{
			//重复分享
			echo '{"code":"202"}';
		}

	}

}
?>