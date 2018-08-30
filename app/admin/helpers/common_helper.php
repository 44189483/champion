<?php
/**
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/array_helper.html
 */

// --------------------------------------------------------------------
if (!function_exists('jump')){
	/**
	 * JS跳转方法:jump
	 * @param	$msg - 提示文字
	 * @param	$url - 跳转URL
	 * @return	JS
	*/
	function jump($msg = '',$url = ''){
        $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script>';
        if(!empty($msg)){
        	$str .= 'alert("'.$msg.'");';
        }
        if(!empty($url)){
        	$str .= 'window.top.location="'.$url.'";';
        }
        $str .= '</script><noscript>如果您禁止了Javascript,请启用，否则请手工回到主页。感谢！</noscript>';
        echo $str;
    }

}

if(!function_exists('get_ip')){

    /**
     * 获取IP方法 get_ip
     * @return  ip
    */
    function get_ip(){
       if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
           $ip = getenv("HTTP_CLIENT_IP");
       else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
           $ip = getenv("HTTP_X_FORWARDED_FOR");
       else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
           $ip = getenv("REMOTE_ADDR");
       else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
           $ip = $_SERVER['REMOTE_ADDR'];
       else
           $ip = "unknown";
           
       $tmp = explode(',', $ip);
       if (count($tmp)>1){
           $ip = $tmp[0];
       }
       $len = strlen($ip);
       if ($len>15){
           $ip = substr($ip,0,15);
       }
       return $ip;
    }

}

if(!function_exists('utfSubstr')){

  /**
   * 截取字数方法 utfSubstr
   * @return string
  */
  function utfSubstr($str, $position, $length,$type=1){

    $startPos = strlen($str);
    $startByte = 0;
    $endPos = strlen($str);
    $count = 0;
    for($i=0; $i<strlen($str); $i++){
      if($count>=$position && $startPos>$i){
        $startPos = $i;
        $startByte = $count;
      }
      if(($count-$startByte) >= $length) {
        $endPos = $i;
        break;
      }
      $value = ord($str[$i]);
      if($value > 127){
        $count++;
      if($value>=192 && $value<=223) $i++;
      elseif($value>=224 && $value<=239) $i = $i + 2;
      elseif($value>=240 && $value<=247) $i = $i + 3;
      else return self::raiseError("\"$str\" Not a UTF-8 compatible string", 0, __CLASS__, __METHOD__, __FILE__, __LINE__);
      }
      $count++;

    }
    if($type==1 && $endPos>$length){
      return substr($str, $startPos, $endPos-$startPos)."...";
    }else{
        return substr($str, $startPos, $endPos-$startPos);
    }

  }

}

if(!function_exists('getRandChar')){
  /**
  * 随机生成字符串
  * @param int $length
  * @return null|string
  */
  function getRandChar($length = 8){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    //0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()-_ []{}<>~`+=,.;:/?|
    $max = strlen($strPol)-1;
    for($i = 0;$i < $length; $i++){
      $str.= $strPol[rand(0,$max)];
    }
    return $str;
  }
}


/*
* 获取随机数
* $length - 字符长度
* $type   - 类型 数字int/字符串型str
*/
function randcode($length=4,$type='int') {  

  if(empty($type) || $type == 'int'){
    $chars = "0123456789";
  }

  if($type == 'str'){
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
  }
  
  $str ="";  
  for ( $i = 0; $i < $length; $i++ )  {  
    $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
  }  
  return $str;  
}

/* 
*发送GET请求方法 
*@param string $url URL 
*@param bool $ssl  是否为https协议 
*@return string   响应主体内容  
*/  
function request($url,$data=null,$type='array'){ 

    $curl = curl_init(); 

    curl_setopt($curl, CURLOPT_URL, $url);  

    //设定为不验证证书和host  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  

    if(!empty($data)){ 

      if($type == 'array'){
        curl_setopt($curl, CURLOPT_POST, true);  
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
      }

      if($type == 'json'){

        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));

      }
          
    }  

    // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出  
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);  

    $output = curl_exec($curl);  
    if (false === $output) {  
        echo "<br/>",curl_error($curl),"<br/>";  
        return false;  
    } 

    curl_close($curl);

    return $output; 

}