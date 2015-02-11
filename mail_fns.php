<?php
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
?>