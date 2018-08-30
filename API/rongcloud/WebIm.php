<?php
/*
* WebIm 融云即时通讯相关
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/

include 'rongcloud.php';

class WebIm{

	private static $baseurl = "http://101.37.107.121/";

	private static $appKey = "mgb7ka1nm4esg";

	private static $appSecret = "JiKsJNCCobSkcL";

	private static $jsonPath = "jsonsource/";

	private $RongCloud;

	public function __construct(){
		$this->RongCloud = new RongCloud(self::$appKey,self::$appSecret);
	}

	/*
	 * 返回融云IM TOKEN
	 * @param  [int] $id
	 * @param  [string] $name   
	 * @param  [string] $avatar 
	 * @return [string] token
	*/
	public function getImToken($id,$name,$avatar){

		$result = $this->RongCloud->user()->getToken($id, $name, self::$baseurl.$avatar);

		$obj = json_decode($result);

		return $obj->token;

	}

	/*
	 * 融云创建群组
	 * @param  [array]  $members   [会员]
	 * @param  [int]    $groupId   [组ID]   
	 * @param  [string] $groupName [组名称] 
	 * @return [string] code       [code码]
	*/
	public function createGroup($members,$groupId,$groupName){

		$result = $this->RongCloud->group()->create($members, $groupId, $groupName);

		$obj = json_decode($result);

		return $obj->code;

	}

	/*
	 * 融云用户加入群组
	 * @param  [array]  $mid       [会员]
	 * @param  [int]    $groupId   [组ID]   
	 * @param  [string] $groupName [组名称] 
	 * @return [string] code       [code码]
	*/
	function joinGroup($mid,$groupId,$groupName){

		$members = array($mid);
		
		return $this->RongCloud->group()->join($members, $groupId, $groupName);

	}

	/*
	 * 融云用户踢出群组
	 * @param  [array]  $mid       [会员]
	 * @param  [string] $groupName [组名称] 
	*/
	function kickGroup($mid,$groupName){

		$members = array($mid);

		return $this->RongCloud->group()->quit($members, $groupName);

	}

	/*
	 * 同步融云群组
	 * @param  [int] $id 用户ID
	 * @param  [array] $groupInfo 用户群组ID
	 * @return 
	*/
	function syncGroup($id,$groupInfo = array()){

		return $this->RongCloud->group()->sync($id, $groupInfo);

	}

}

?>