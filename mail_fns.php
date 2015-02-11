<?php
function account_exists($auth_user, $account){
	if($auth_user !=="" && $account!==""){
		return true;
	}else{
		return false;
	}
}
function get_accounts($auth_user){
	$list = array();
	if($conn = db_connect()){
		$query = "select * from accounts where username = '".$auth_user."'";
		$result = $conn->query($query);
		
		if($result){
			while($setting = $result->fetch_assoc()){
				array_push($list, $setting);
			}
		}else{
			return false;
		}
	}
	return $list;
}
function number_of_accounts($auth_user){
	//get the number of accounts that belong to this user
	$query = "select count(*) from accounts where
			  username = '".$auth_user."'";
	if($conn = db_connect()){
		$result = $conn->query($query) or die($conn->error);
		if($result){
			$row = $result->fetch_array();
			return $row[0];
		}
	}
}
function get_account_list($auth_user){
	//get the user for mail
	$list = array();
	$query = "select * from accounts where
			  username = '".$auth_user."'";
	if($conn = db_connect()){
		$result = $conn->query($query) or die($conn->error);
		if($result){
			while($settings = $result->fetch_assoc()){
				array_push($list, $settings['remoteuser']);
			}
		}else{
			return false;
		}
	}
	return $list;
}
function get_account_settings($auth_user, $accountuid){
	//get the user for mail
	
	$query = "select * from accounts where
			  username = '".$auth_user."'
			  and remoteuser='".$accountuid."'";
	if($conn = db_connect()){
		$result = $conn->query($query) or die($conn->error);
		if($result){
			$setting = $result->fetch_assoc();
			
			return $setting;
			
		}else{
			return false;
		}
	}
	
}
function open_mailbox($auth_user, $accountid){
  //select mailbox if there is only one
  if(number_of_accounts($auth_user) == 1){
  	$accounts = get_account_list($auth_user);
  	$_SESSION['selected_account'] = $accounts[0];
  	$accountid = $accounts[0];
  }
  
  //conncet to the POP3 or IMAP server the user has selected
  $settings = get_account_settings($auth_user, $accountid);
  if(!sizeof($settings)){
  	return 0;
  }
  $mailbox = '{'.$settings['server'];
  if($settings['type'] == 'POP3'){
  	$mailbox .= '/POP3';
  }
  $mailbox .=':'.$settings['port'].'}INBOX';
  
  //suppress warining, remember to check return value
  $imap = imap_open($mailbox, $settings['remoteuser'],$settings['remotepassword']);
  
  return $imap;
}
function retrieve_message($auth_user, $accountid, $messageid, $fullheaders){
	$message = array();
	
	if(!($auth_user && $accountid && $messageid)){
		return false;
	}
	
	$imap = open_mailbox($auth_user, $accountid);
	
	if(!$imap){
		return false;
	}
	
	$header = imap_header($imap, $messageid);
	
	if(!$header){
		return false;
	}
	$message['body'] = imap_body($imap, $messageid);
	
	if(!$message['body']){
		$message['body'] = "[This message has no body]\n\n\n\n\n\n";
	}
	
	if($fullheaders){
		$message['fullheaders'] = imap_fetchheader($imap, $messageid);
	}else{
		$message['fullheaders'] = '';
	}
	
	$message['subject'] = $header->subject;
	$message['fromaddress'] = $header->fromaddress;
	@$message['toaddress'] = $header->toaddress;
	@$message['ccaddress'] = $header->ccaddress;
	$message['date'] = $header->date;
	
	//note we can get more detailed information by using from and to
	//rather than fromaddress and toaddress,but these are easier
	
	imap_close($imap);
	return $message;
}
function base64_imapdecode($string){
	$pattern = "/=\?UTF-8\?B\?(.*)=/";
	if(preg_match($pattern,$string) == true){
		preg_match($pattern,$string,$regs);
		$string = @base64_decode($regs[1]);
	}
	
	return $string;
}
function decode_qprint($str){
	$arr= array("A","B","C","D","E","F");
	while (list(, $var) = each($arr)) {
		$i=0;
		while ($i <=9){
			$str=str_replace("=".$var.$i,"%".$var.$i,$str);
			$str = str_replace("=".$i.$var,"%".$i.$var,$str);
			for($j=0;$j<10;$j++){
				$str = str_replace("=".$i.$j,"%".$i.$j,$str);
			}
			$i++;}
			$arr2 = array("A","B","C","D","E","F");
			while (list(, $val) = each($arr2)) {
				$str=str_replace("=".$var.$val,"%".$var.$val,$str);
			}
			
	}
				$str = (preg_replace("/=/","",$str));
				$str = preg_replace("/style\"/","style=\"",$str);
				$str = preg_replace("/a\"/","a=\"",$str);
				//替换空格键（\n\r\t）
				$str = preg_replace("/[\n\r]/","",$str);
				preg_match("/charset\"(\w*)\"/",$str,$regs);
				
				return urldecode($str);}
?>