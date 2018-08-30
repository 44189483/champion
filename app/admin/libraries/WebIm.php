<?php
/*
* WebIm 融云WEBIM相关
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

include '/API/rongcloud/rongcloud.php';

class WebIm{

	public $appKey = 'mgb7ka1nm4esg';

	public $appSecret = 'JiKsJNCCobSkcL';

	public $rongcloud;

	public function __construct($appKey = "" ,$appSecret = ""){
		$this->appkey = $appKey;
		$this->appsecret = $appSecret;
		$jsonPath = "/API/rongcloud/jsonsource/";
		$this->rongcloud = new RongCloud($this->appkey,$this->appsecret);
	}

	/*
	 * 融云用户加入群组
	 * @param  [array]  $mid       [会员]
	 * @param  [int]    $groupId   [组ID]   
	 * @param  [string] $groupName [组名称] 
	 * @return [string] code       [code码]
	*/
/*
	public function joinGroup($mid,$groupId,$groupName){

		$members = array($mid);

		$rongcloud = $this->rongcloud();
		
		//$result = 
		$rongcloud->group()->join($members, $groupId, $groupName);
		//$obj = json_decode($result);
		//return $obj->code;
	}
	*/

	/*
	 * 融云用户踢出群组
	 * @param  [array]  $mid       [会员]
	 * @param  [string] $groupName [组名称] 
	*/
/*
	public function kickGroup($mid,$groupName){

		$members = array($mid);

		$rongcloud = $this->rongcloud();

		$rongcloud->group()->quit($members, $groupName);
	
	}*/

	/*
	 * 同步融云群组
	 * @param  [int] $id 用户ID
	 * @param  [array] $groupInfo 用户群组ID
	 * @return 
	*/
/*
	public function syncGroup($id,$groupInfo = array()){

		$RongCloud = $this->rongcloud();

		
		//获取当前用户所有群组
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
				m.memberId={$id}
				AND
				m.isDeleted=0
		";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		foreach ($result as $key => $val) {
			$groupInfo[$val['id']] = $val['name'];
		}
		
	
		$RongCloud->group()->sync($id, $groupInfo);

	}*/

}
?>