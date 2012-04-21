<?php

$start = time();

include '_lib.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <head>
  <title>Geo-RIS</title> </head>   
    <link rel="stylesheet" href="/css/style.css" type="text/css" />
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    
    <script src="http://maps.google.com/maps/api/js?v=3.6&amp;sensor=false"></script>
    <script src="/js/OpenLayers.js"></script>
    <script src="/js/lib/Firebug/firebug.js"></script>
    <script src="/js/map.js"></script>
    <?if(!$_SESSION["user"]["geolocat"]){?>
    	<script src="/js/geolocat.js"></script>
    <?}?>
    
    
  </head>

<?php 

$LOGO='<div style="float:right">
		  <a href="/">
		    <img style="z-index: 1; margin-right: 20px; margin-top: -5px;" border="0" src="/img/logo.png">
		  </a>
		</div>';


if ($_GET["load"]){
	if (file_exists("view/".$_GET["load"].".php")){
		include_once "view/".$_GET["load"].".php";
	}else{
		$_GET["text"] = "404";
		include_once "view/text.php";
	}
}else{
	$_GET["text"] = "home";
	include_once "view/text.php";
}

if ((time()-$start) > 2){?>
<p>Laufzeit: <?=time()-$start?> Sekunden</p>
<?php }?>
</html>