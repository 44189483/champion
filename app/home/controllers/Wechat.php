<?php
/*
* Wechat 微信相关
* @package	Wechat
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
class Wechat extends CI_Controller{

	public function __construct(){
		
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		if(empty($this->session->member->id) || $this->session->member->status == 0){
			redirect(site_url('/member/login'));
		}

	}

	//生成二维码图片
	public function qrcode(){

		$url = urldecode($this->input->get("url"));

		if(empty($url)){
			show_error('URL不存在',null,'提示');
		}

		$this->load->library('Phpqrcode');

        //二维码内容  
	      
	    $errorCorrectionLevel = 'L';    //容错级别   
	    $matrixPointSize = 10;           //生成图片大小    
	      
	    //生成二维码图片  
	    $filename = '/upload/qrcode/'.date('YmdHis').'.png';  
	    QRcode::png($url,$_SERVER['DOCUMENT_ROOT'].$filename , $errorCorrectionLevel, $matrixPointSize, 2);    
	    
	    //$QR = $_SERVER['DOCUMENT_ROOT'].$filename; //已经生成的原始二维码图片文件    

	    //$QR = imagecreatefromstring(file_get_contents($QR));    
	    
	    //输出图片    
	    //imagepng($QR, 'qrcode.png');    
	    //imagedestroy($QR);  
	    echo '
		<html lang="en">
		<head>
			<meta charset="UTF-8" />
			<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="apple-mobile-web-app-status-bar-style" content="black" />
			<meta name="format-detection" content="telephone=no" />
		</head>
		<body>
		    <p align="center">
		    	<img src="'.$filename.'" alt="使用微信扫描" />
		    </p>
		    <p align="center">
		    	使用微信扫描
		    </p>
		</body>
	    </html>
	    ';    
        
	}

}
?>