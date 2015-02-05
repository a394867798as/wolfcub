<?php
function display_login_form($action){
?>
 <form action="index.php" method="post" >
 <table bgcolor='#cccccc' border='0' align="center" cellpadding="4" cellspacing="1" width="300" style="margin-top:100px;">
  <tr>
   <th colspan="2" bgcolor="#666666" align="center" height="45" >Please Log In</th>
  </tr>
  
  <tr height="45">
   <td>Username:</td>
   <td><input type="text" name="username" style="width:150px" /></td>
  </tr>
  <tr height="45">
   <td>Password:</td>
   <td><input type="password" name="passwd" style="width:150px" /></td>
  </tr>
  <tr height="45">
   <td colspan="2" align="center"><input type="submit" value="Log In"  style="background-image:url(images/mail.jpg);"/></td>
  </tr>
 </table>
 </form>
<?php 
}
function do_html_header($username,$title,$selected=""){
?>
<html>
<head>
<title><?php echo $title; ?></title>
<link href="css/css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="top">
<div id="header">
 <span class="famly"><img src="images/mail.jpg" height="50" /></span>
 <span class="famly"><?php echo $title;?></span>
 <?php
 if(number_of_accounts($username)==1){
  echo "<span class='auth_user'>".$selected."</span>";
 }
 if(number_of_accounts($username)>1){
 	echo "<span class='auth_user'><form action='index.php?action=open-mailbox' method='post'>
 		   ";
 	display_account_select($username, $selected);
 	echo "
 		  </form></span>";
 }
?>
</div>
<?php
}
function display_account_select($username,$selected){
?>
 <select onChange="window.location=this.options[selectedIndex].value" name="account">
  <?php
   $list = get_accounts($username);
   $accounts = count($list);
   foreach ($list as $key => $account){
   	echo "<option value='index.php?action=select-account&account=".$account['accountid']."'>".$account['remoteuser']."</option>";
   }
  ?>
 </select>
<?php 
}
function  display_toolbar($buttons){
	echo "<div id='nav'>
 			<ul>";
	foreach($buttons as $nav){
		
		if($nav == 'log-out'){
			echo "<li style='float:roght;'><a href='index.php?action=".$nav."'>".$nav."</a></li>";
		}else{
			echo "<li><a href='index.php?action=".$nav."'>".$nav."</a></li>";
		}
	}
	echo "</ul></div></div>";
}
function do_html_footer(){
	?>
    <table width="100%" bgcolor="#999999" >
     <tr>
      <td><img src="images/mail.jpg" width="49" height="50" align="right" /></td>
     </tr>
    </table>
    <?php
	
}
function display_account_form($auth_user,$accountid=0,$server="",$remoteuser="",$remotepassword="",$type="",$port="143"){
	if($accountid!=0){
		?>
		<form action="index.php?action=store-settings" method="post" >
		 <table bgcolor='#cccccc' border='0' align="center" cellpadding="4" cellspacing="1" width="400" style="margin-top:10px;">
		  <tr>
		   <th colspan="2" bgcolor="#666666" align="center" height="45" >Accout</th>
		  </tr>
		  
		  <tr height="45">
		   <td>Server Name:</td>
		   <td><input type="text" name="server" style="width:150px" value="<?php echo $server; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Port Number:</td>
		   <td><input type="text" name="port" style="width:150px" value="<?php echo $port; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Server Type:</td>
		   <td>
		    <select name="type">
		     <?php 
		      $typeArray = array('IMAP','POP3');
		      foreach ($typeArray as $vaule){
		      	if($type == $vaule){
		      		echo "<option value='".$vaule."' selected >".$vaule."</option>";
		      	}else{
		      		echo "<option value='".$vaule."' >".$vaule."</option>";
		      	}
		      }
		     ?>
		    </select>
		   </td>
		  </tr>
		  <tr height="45">
		   <td>Username:</td>
		   <td><input type="text" name="remoteuser" style="width:150px" value="<?php echo $remoteuser; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Password:</td>
		   <td><input type="password" name="remotepassword" style="width:150px" <?php echo $remotepassword; ?> /></td>
		  </tr>
		  <tr height="45">
		   <td>
		   <input type="hidden" name="account" value="<?php echo $accountid; ?>" />
		   
		   </td>
		   <td align="left">
		   <input type="submit" value="Save Changes"  style="background-image:url(images/mail.jpg);"/>
		   <div style="background:#DDD; width:150px; float:right; text-align:center;" class="button">
		   <a href="index.php?action=delete-account&account=<?php echo $accountid ?>" >Delete Account</a></div>
		   </td>
		   
		  </tr>
		 </table>
		</form>
		<?php 
	}else{
		?>
		<form action="index.php?action=store-settings" method="post" >
		 <table bgcolor='#cccccc' border='0' align="center" cellpadding="4" cellspacing="1" width="300" style="margin-top:100px;">
		  <tr>
		   <th colspan="2" bgcolor="#666666" align="center" height="45" >New Accout</th>
		  </tr>
		  
		  <tr height="45">
		   <td>Server Name:</td>
		   <td><input type="text" name="server" style="width:150px" value="<?php echo $server; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Port Number:</td>
		   <td><input type="text" name="port" style="width:150px" value="<?php echo $port; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Server Type:</td>
		   <td>
		    <select name="type">
		     <?php 
		      $typeArray = array('IMAP','POP3');
		      foreach ($typeArray as $vaule){
		      	if($type == $vaule){
		      		echo "<option value='".$vaule."' selected >".$vaule."</option>";
		      	}else{
		      		echo "<option value='".$vaule."' >".$vaule."</option>";
		      	}
		      }
		     ?>
		    </select>
		   </td>
		  </tr>
		  <tr height="45">
		   <td>Username:</td>
		   <td><input type="text" name="remoteuser" style="width:150px" value="<?php echo $remoteuser; ?>" /></td>
		  </tr>
		  <tr height="45">
		   <td>Password:</td>
		   <td><input type="password" name="remotepassword" style="width:150px" <?php echo $remotepassword; ?> /></td>
		  </tr>
		  <tr height="45">
		   <td><input type="hidden" name="account" value="<?php echo $accountid; ?>"  /></td>
		   <td align="center">
		   <input type="submit" value="Save Changes"  style="background-image:url(images/mail.jpg);"/>
		    
		   </td>
		  </tr>
		 </table>
		</form>
		<?php 
	}
}
function display_list($auth_user,$accountid){
	//show the list of message in this mailbox
	global $table_width;
	if(!$accountid){
		echo "<p style='padding-bottom:100px'>No mailbox selected.</p>";
	}else{
		$imap = open_mailbox($auth_user, $accountid);
		
		if($imap){
			echo "<table width='".$table_width."' cellspacing='0' cellpadding='6' border='0'>";
			$headers = imap_header($imap);
			//we could reformat this date, or get other details using
			//imap_fetchheaders, but this is not a bad summary so we
			//just echo each
			$messages = sizeof($headers);
			for($i=0;$i<$messages;$i++){
				echo "<tr><td bgcolor='";
				if($i%2==0){
					echo "#ffffff";
				}else{
					echo "#ffffcc";
				}
				echo "'><a href='index.php?action=view-message&messageid=".($i+1)."'>";
				echo $headers[$i];
				echo "</a></td></tr>\n";
		 	}
			echo "</table>"; 
		}else{
			$account = get_account_settings($auth_user,$messages);
			echo "<p style='padding-bottom:100px'>Could not open email box
				  ".$account['server']."</p>";
		}
	}
}
?>