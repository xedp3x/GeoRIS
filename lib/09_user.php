<?php

function USER_set($user){
	SQL_insert_update('user',$user);
}

function USER_login($email, $pass){
	global $_SESSION;
	
	$user = SQL_select_one('user',"mail = '$email'");
	
	if ($user["pass"] <> md5($pass)){
		return false;
	}
	
	$_SESSION["user"] = array(
		"geolocat" 	=> array(
			"lon" 	=> $user["lon"],
			"lat" 	=> $user["lat"]
		),
		"default" 	=> array(
			"template" => $user["template"]
		),
		"group"		=> $user["group"],
		"radius"	=> $user["radius"],
		"mail"		=> $user["mail"]
	);
	
	return true;
}


function USER_list(){
	return SQL_select_as_array('user',"`group` <> 'user'", "*", "`group` DESC");
}