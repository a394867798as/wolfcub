<?php
function check_auth_user(){
	if(isset($_SESSION['auth_user'])){
		return true;
	}else{
		return false;
	}
}

function login($username,$passwd){
	//connect the database
	$conn = db_connect();
	$query = "select * from users 
			  where username = '".$username."'
			  and password = '".sha1($passwd)."'";
	$result = $conn->query($query) or die($conn->error);
	
	if($result->num_rows>0){
		return true;
	}else{
		return false;
	}
}


function format_action($action){
	$action = preg_replace("/-/"," ",$action);
	$action = ucwords($action);
	return $action;
}
function display_account_setup($auth_user){
	//display empty 'new account' form
	display_account_form($auth_user);
	$list = get_accounts($auth_user);
	$accounts = sizeof($list);
	
	//display each stored account
	foreach ($list as $key=> $account){
		//display form for each accounts details
		//note that we are going to send the password for all accounts in the HTML
		//this is not really a very goo idear
		display_account_form($auth_user, $account['accountid'], $account['server'],$account['remoteuser'],
							 $account['remotepassword'],$account['type'],$account['port']);
	}
}
function store_account_settings($auth_user,$settings){
	if(!filled_out($settings)){
		echo "<p>All fields must be filled in. Try again.</p>";
		return false;
	}else{
		if($settings['account']>0){
			$query = "update accounts set server = '".$settings['server']."',
					  port = '".$settings['port']."', type='".$settings['type']."',
					  remoteuser = '".$settings['remoteuser']."', remotepassword = '".$settings['remotepassword']."'
					  where accountid = '".$settings['account']."'
					  and username = '".$auth_user."'";
			
		}else{
			$query = "insert into accounts values('".$auth_user."',
				  '".$settings['server']."','".$settings['port']."',
				  '".$settings['type']."','".$settings['remoteuser']."',
				  '".$settings['remotepassword']."',NULL)";
	
		}
		if($conn=db_connect()){
			$result = $conn->query($query) or die($conn->error);
			if($result){
				return true;
			}else{
				return false;
			}
		}else{
			echo "<p>Could not store changes</p>";
			return false;
		}
	}
}
function delete_account($auth_user, $accountid){
	//delete one of this user's accounts from the DB
	
	$query = "delete from accounts where accountid = '".$accountid."'
			  and username = '".$auth_user."'";
	if($conn = db_connect()){
		$result = $conn->query($query) or die($conn->error);
	}
	return $result;
}
?>