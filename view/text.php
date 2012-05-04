<?php

/*
 * Läd Texte aus dem "/text" Ordner
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */


if (file_exists("text/".$_GET["text"]."_".$_SESSION["lang"].".php")){
	include_once ("text/".$_GET["text"]."_".$_SESSION["lang"].".php");
}elseif (file_exists("text/".$_GET["text"].".php")){
	include_once ("text/".$_GET["text"].".php");
}elseif (file_exists("text/404.php")){
	include_once ("text/404.php");
}else{
	echo t("<h2>Fehler 404</h2> <br /> Die Seite konte nicht gefunden werden");
}
?>