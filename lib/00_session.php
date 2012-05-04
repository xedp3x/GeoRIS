<?php
session_start();
if (! $_COOKIE["Cookie"])
	setcookie("Cookie", "true");
		
if ($_POST["setcookie"])
	setcookie($_POST["setcookie"], $_POST[$_POST["setcookie"]]);
	
if ($_GET["setcookie"])
	setcookie($_GET["setcookie"], $_GET[$_GET["setcookie"]]);