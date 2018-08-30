<?php
/*
* EXCEL操作类
* @package	User
* @author	Sun Guo Liang
* @since	Version 1.0.0
* @filesource
*/
error_reporting(E_ALL^E_NOTICE^E_WARNING);

class Excel extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->library('PHPExcel');
		//$this->load->helper('url_helper');
		$this->load->database();

		if(empty($this->session->admin)){
			//show_error('<a href="'.base_url().'index/login">请登录!</a>',null,'错误提示');
			jump('',site_url('index/login'));
		}

	}

	/** 
	 * 数据导出 
	 * @param array $title   标题行名称 
	 * @param array $data   导出数据 
	 * @param string $fileName 文件名 
	 * @param string $savePath 保存路径 
	 * @param $type   是否下载  false--保存   true--下载 
	 * @return string   返回文件全路径 
	 * @throws PHPExcel_Exception 
	 * @throws PHPExcel_Reader_Exception 
	*/  
	function exportExcel($title=array(), $data=array(), $fileName='', $savePath='./', $isDown=false){  
 
	    $obj = new PHPExcel();  
	  
	    //横向单元格标识  
	    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
	      
	    $obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称  
	    $_row = 1;   //设置纵向单元格标识  
	    if($title){  
	        $_cnt = count($title);  
	        //$obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$_cnt-1].$_row);   //合并单元格  
	        $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容  
	        //$_row++;  
	        $i = 0;  
	        foreach($title AS $v){   //设置列标题  
	            $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);  
	            $i++;  
	        }  
	        $_row++;  
	    }  
	  
	    //填写数据  
	    if($data){  
	        $i = 0;  
	        foreach($data AS $_v){  
	            $j = 0;  
	            foreach($_v AS $_cell){  
	                $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);  
	                $j++;  
	            }  
	            $i++;  
	        }  
	    }  
	      
	    //文件名处理  
	    if(!$fileName){  
	        $fileName = uniqid(time(),true);  
	    }  
	  
	    $objWrite = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');  
	  
	    if($isDown){   //网页下载  
	        header('pragma:public');  
	        header("Content-Disposition:attachment;filename=$fileName.xls");  
	        $objWrite->save('php://output');exit;  
	    }  
	  
	    $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码  
	    $_savePath = $savePath.$_fileName.'.xlsx';  
	     $objWrite->save($_savePath);  
	  
	    return $savePath.$fileName.'.xlsx';  
	}  
	  
	//EXCEL导出
	function export(){

		$sql = "
			SELECT 
				realname,
		    	nation,
		    	phone,
		    	email,
		    	identificationNumber,
				CASE 
					WHEN level = 0 THEN '普通会员' 
                	WHEN level = 1 THEN '志愿者'
                	WHEN level = 2 THEN '媒体'
            	END AS type,  
		    	integral,
		    	birthAddress, 
		    	liveAddress,
		    	contactAddress,
		    	createdAt 
			FROM 
			users
		";
		
		$query = $this->db->query($sql);
	    $rows = $query->result_array();

	    $fieldArr = array(
	    	'realname' => '姓名',
	    	'nation' => '民族',
	    	'phone' => '手机',
	    	'email' => '邮箱',
	    	'identificationNumber' => '身份证',
	    	'type' => '类型',
	    	'integral' => '积分',
	    	'birthAddress' => '出生地', 
	    	'liveAddress' => '居住地',
	    	'contactAddress' => '通信地址',
	    	'createdAt' => '加入时间'
	    );

	    $this->exportExcel($fieldArr, $rows, '会员报表'.date('Ymdhis'), 'upload/excel/', true); 

	} 

	/** 
	*  数据导入 
	* @param string $file excel文件 
	* @param string $sheet 
	 * @return string   返回解析数据 
	 * @throws PHPExcel_Exception 
	 * @throws PHPExcel_Reader_Exception 
	*/  
	function importExecl($file='', $sheet=0){ 

		include '/API/rongcloud/WebIm.php';

		$rongcloud = new WebIm();

	    $file = iconv("utf-8", "gb2312", $file);   //转码  
	    if(empty($file) OR !file_exists($file)) {  
	        die('file not exists!');  
	    }  
	    
	    $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
	    if(!$objRead->canRead($file)){  
	        $objRead = new PHPExcel_Reader_Excel5();  
	        if(!$objRead->canRead($file)){  
	            die('No Excel!');  
	        }  
	    }  
	  
	    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
	  
	    $obj = $objRead->load($file);  //建立excel对象  
	    $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表  
	    $columnH = $currSheet->getHighestColumn();   //取得最大的列号  
	    $columnCnt = array_search($columnH, $cellName);  
	    $rowCnt = $currSheet->getHighestRow();   //获取总行数  
	  
	    $data = array();  
	    for($_row=2; $_row<=$rowCnt; $_row++){  //读取内容  
	        
	        //for($_column=0; $_column<=$columnCnt; $_column++){  
	            
	            //$cellId = $cellName[$_column].$_row;  
	            //$cellValue = $currSheet->getCell($cellId)->getValue();  
	             //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值  
	            //if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串  
	                //$cellValue = $cellValue->__toString();  
	            //}  
	  
	            //$data[$_row][$cellName[$_column]] = $cellValue;
	        //}
	        //    
            $realname   			= $currSheet->getCell("A".$_row)->getValue();
            $nation    				= $currSheet->getCell("B".$_row)->getValue();
            $phone  				= $currSheet->getCell("C".$_row)->getValue();
			$email      		  	= $currSheet->getCell("D".$_row)->getValue();
			$identificationNumber 	= $currSheet->getCell("E".$_row)->getValue();
			$level1             		= $currSheet->getCell("F".$_row)->getValue();
			$integral             	= $currSheet->getCell("G".$_row)->getValue();
			$birthAddress         	= $currSheet->getCell("H".$_row)->getValue();
			$liveAddress          	= $currSheet->getCell("I".$_row)->getValue();
			$contactAddress       	= $currSheet->getCell("J".$_row)->getValue();
			$createdAt       		= $currSheet->getCell("K".$_row)->getValue();

			//0.普通会员1.志愿者2.记者
			switch ($level1) {
				case '记者':
					$level = 2;
					$code = '';
					break;
				case '志愿者':
					$level = 1;
					$code = strtoupper(randcode(8,'str'));
					break;
				case '普通会员':
					$level = 0;
					$code = '';
					break;
				default:
					$level = 0;
					$code = '';
					break;
			}

			$rand = rand(1000,9999);
			$passwordHash = sha1('123456|'.$rand);
			$passwordSalt = $rand;

			//手机号或身份证号重复过滤
			$query = $this->db->query("SELECT id FROM users WHERE phone={$phone}");
			$num = $query->num_rows();
			if($num == 0){

				//$data_values .= "('$realname','$nation','$phone','$email','$identificationNumber','$level','$integral','$birthAddress','$liveAddress','$contactAddress','$createdAt',1,1,'$code','$passwordHash','$passwordSalt'),";
				
				$data = array(
					'region' => '86',
				    'realname' => $realname,
				    'passwordHash' => $passwordHash,
				    'passwordSalt' => $passwordSalt,
				    'nation' => $nation,
				    'phone' => $phone,
				    'email' => $email,
				    'identificationNumber' => $identificationNumber,
				    'level' => $level,
				    'integral' => $integral,
				    'birthAddress' => $birthAddress,
				    'liveAddress' => $liveAddress,
				    'contactAddress' => $contactAddress,
				    'inviteCode' => $code,
				    'activation' => 1,
				    'status' => 1,
				    'createdAt' => date('Y-m-d H:i:s')
				);
				$this->db->insert('users', $data);
								
				if($level == 1){
					//志愿者更新WEBIM TOKEN
				 	$id = $this->db->insert_id();
				 	$token = $rongcloud->getImToken($id,$realname,$data['portraitUri']);
				 	$data['rongCloudToken'] = $token;	
				 	$this->db->where('id', $id);
				 	$this->db->update('users', $data);	
				}
				
			} 

	    } 
	    // $data_values = substr($data_values,0,-1); //去掉最后一个逗号 
	    // if(!empty($data_values)){
	    // 	$query = $this->db->query("INSERT INTO users(realname,nation,phone,email,identificationNumber,level,integral,birthAddress,liveAddress,contactAddress,createdAt,activation,status,inviteCode,passwordHash,passwordSalt) VALUES {$data_values}");//批量插入数据表中    
	    // }
	    echo 'success';
	    unlink($file);
	    // if($query){   
	    //     echo 'success';
	    //     unlink($file);   
	    // }else{   
	    //     echo 'error';   
	    // } 
	      
	}  

	//导入EXCEL
	function import(){
		$tmp = $_FILES['file']['tmp_name'];   
		if (empty($tmp)) {   
		    echo 'error';   
		    exit;   
		}        
		$save_path = "upload/excel/";   
		$file_name = $save_path.date('Ymdhis') . ".xls"; //上传后的文件保存路径和名称   
		if (copy($tmp, $file_name)) {
			$this->importExecl($file_name);  
		} 
	}

}
?>