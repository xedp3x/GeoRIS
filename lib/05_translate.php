<?php

if ($_GET["lang"])
	$_SESSION["lang"] = $_GET["lang"];

$l = explode('/',$_SERVER["REQUEST_URI"]);
if (strlen($l[1])==2){
	$_SESSION["lang"] = $l[1];
	$_t = "/".$l[1];
}
unset($l);
	
/*
if (file_exists("local/".$_SESSION["lang"].".php"))
	include "local/".$_SESSION["lang"].".php";
*/
	
function t($in){
	global $_SESSION;
	global $_TRANS;
	
	if ($_TRANS[$in]){
		return $_TRANS[$in];
	}else{
		if (!isset($_TRANS[$in]))$_SESSION["debug"]["lang_miss"][$in] = false;
		return $in;
	}
}