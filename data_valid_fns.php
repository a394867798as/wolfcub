<?php
function filled_out($form_vars){
	//test that each variable a value
	foreach ($form_vars as $key => $value){
		if((!isset($key)) || $value==""){
			return false;
		}
	}
	return  true;
}
?>