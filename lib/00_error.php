<?php

function DebMsg($msg){
	global $_SESSION;
	$_SESSION["debug"][] = $msg;
}

function logging($message){
	// LOG
}


function error($ID, $Fehler){
	echo "Der Fehler $ID ist aufgetreten :-(<br /><br />
	<img src='img/sorry.png' /> <br /> <br />
	Der Fehler Lautet <br />
	<pre>$Fehler</pre>";

	logging("Error $ID - $Fehler");
	die ("<br /> Ablauf wurde gestoppt");
}