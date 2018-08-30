<?php
/*
* Message 留言及意见反馈相关
* @package	Message
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class Message extends CI_Controller{

	public function __construct(){
		
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
		
	}

	//提交留言
	public function save(){

		//1.在线留言2.意见反馈
		$type = $this->input->post('type');

		$content = strip_tags($this->input->post('content'));

		$data = array(
			'type' => $type,
		    'content' => $content,
		    'createTime' => date('Y-m-d H:i:s')
		);
		$bool = $this->db->insert($this->db->dbprefix('message'), $data);
		if($bool){
			echo 1;
		}else{
			echo '留言失败';
		}
        
	}

}
?>