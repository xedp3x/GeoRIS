<?php
if (isset($_GET["logout"])){
	setcookie("login", '', time()-3600);
	$_username = false;
	unset($_COOKIE["login"]);
}

if ($_POST["name"] AND $_POST["pass"] AND ($_POST["type"] == 'Login')){

	$in = USER_detail($_POST["name"]);
	
	if ($in["pass"] == md5($_POST["pass"])){
		$_username = $_POST["name"];
		setcookie("login", "$_username\t".md5($_username.$config["sec"]["Cookikey"]), time()+3600);
	}else{
		$_username = false;
		setcookie("login", '', time()-3600);
		unset($_COOKIE["login"]);
	}
}

if ($_COOKIE["login"]){
	$x = explode("\t",$_COOKIE["login"]);
	if ($x[1] == md5($x[0].$config["sec"]["Cookikey"])){
		$_username = $x[0];
	}else{
		$_username = false;
	}
}