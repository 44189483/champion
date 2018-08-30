<?php
/*
* Ajax INPUT/CHECKBOX 无刷新
* @package	Ajax
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

class Ajax extends CI_Controller{

	public function __construct(){

		parent::__construct();

		$this->load->database();

		$this->id = $this->input->post('id');//ID值

		$this->filedId = $this->input->post('filedId');//字段ID

		$this->filed = $this->input->post('filed');//字段

		$this->value = $this->input->post('value');//值
		
		$this->table = $this->input->post('table');//表

	}

	//删除
	public function del(){

		$query = $this->db->query("SELECT {$this->filed} FROM {$this->table} WHERE {$this->filedId}={$this->id}");
	    $row = $query->row_array();
		
		//删除文件
		@unlink($row[$this->filed]);

		$bool = $this->db->query("UPDATE {$this->table} SET {$this->filed}='' WHERE {$this->filedId}={$this->id}");

		if($bool){
			echo '1';
		}else{
			echo '0';
		}

	}

	public function input(){

		$bool = $this->db->query("UPDATE {$this->table} SET {$this->filed}='{$this->value}' WHERE {$this->filedId}={$this->id}");

		if($bool){
			echo '1';
		}else{
			echo '0';
		}

	}

	public function checkbox(){


		//补丁 报名加积分
		$fileds = '';
		if($this->table == 'signup'){
			$fileds .= ',aid,mid';
		}

		/********/

		$query = $this->db->query("SELECT {$this->filed} {$fileds} FROM {$this->table} WHERE {$this->filedId}={$this->id}");
	    $row = $query->row_array();

	    $val = $row[$this->filed] == 0 ? 1 : 0;

		$this->db->query("UPDATE {$this->table} SET {$this->filed}='{$val}' WHERE {$this->filedId}={$this->id}");

		/********/

		//补丁 报名会员加积分
		if($this->table == 'signup'){
			$que = $this->db->query("SELECT title,integral FROM activity WHERE id={$row['aid']}");
	    	$res = $que->row_array();

	    	include '/API/rongcloud/WebIm.php';

			$rongcloud = new WebIm();

	    	if($val == 1){

	    		//加积分
	    		$f = "integral=integral+{$res['integral']}";

	    		//加入群组
		    	$rongcloud->joinGroup($row['mid'],$row['aid'],$res['title']);

		    	//查询是否曾加入群组
		    	$q = $this->db->query("SELECT * FROM group_members WHERE groupId={$row['aid']} AND memberId={$row['mid']}");
	    		$r = $q->row_array();

	    		if($r){

	    			//更新组成员数据库
					$this->db->query("UPDATE group_members SET isDeleted=0 WHERE groupId={$row['aid']} AND memberId={$row['mid']}");

	    		}else{

	    			//创建组成员数据库
					$data = array(
					    'groupId' => $row['aid'],
					    'memberId' => $row['mid'],
					    'role' => 0,
					    'show' => 1,
					    'timestamp' => time(),
					    'createdAt' => date('Y-m-d H:i:s')
					);
					$this->db->insert('group_members', $data);

	    		}

	    	}else{
	    		//减分
	    		$f = "integral=integral-{$res['integral']}";
	    		//退出群组
	    		$rongcloud->kickGroup($row['mid'],$res['title']);

	    		//更新组成员数据库
				$this->db->query("UPDATE group_members SET isDeleted=1 WHERE groupId={$row['aid']} AND memberId={$row['mid']}");
	    	}

	    	$this->db->query("UPDATE users SET {$f} WHERE id={$row['mid']}");

	    	//同步融云群组
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
					m.memberId={$row['mid']}
					AND
					m.isDeleted=0
			";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			if($result){
				foreach ($result as $key => $val) {
					$groupInfo[$val['id']] = $val['name'];
				}
				$rongcloud->syncGroup($row['mid'],$groupInfo);
			}

		}

		//补丁 志愿者审核
		if($this->table == 'users'){

			$query = $this->db->query("SELECT level,activation FROM users WHERE id={$this->id}");
    		$res = $query->row();

			//志愿者审核通过生成激活码
			if($res->level == 1 && $res->activation == 1){

				$this->load->helper('common_helper');

				//生成随机邀请码
			    $code = strtoupper(randcode(8,'str'));

				$this->db->query("UPDATE users SET inviteCode='{$code}' WHERE id={$this->id}");

			}

		}

	}

}
?>