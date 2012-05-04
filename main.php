<?php
include '_lib.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <head>
  	<title>Geo-RIS</title>
    <link rel="stylesheet" href="/css/style.css" type="text/css" />
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    
    <script src="http://maps.google.com/maps/api/js?v=3.6&amp;sensor=false"></script>
    <script src="/js/OpenLayers.js"></script>
    <script src="/js/lib/Firebug/firebug.js"></script>
    <script src="/js/map.js"></script>
    <script src="/js/map_edit.js"></script>
    <script src="/js/scripte.js"></script>
    <?if(!$_SESSION["user"]["geolocat"]){?>
    	<script src="/js/geolocat.js"></script>
    <?}?>
    
  </head>

<?php 

$LOGO='<div style="float:right">
		  <a href="/">
		    <img style="z-index: 1; margin-right: 20px; margin-top: 0px;" border="0" src="/img/logo.png">
		  </a>
		</div>
		<ul class="arrowunderline">
			<li><a href="/">Karte</a></li>
			<li><a href="/login.html">'.(($_SESSION["user"]["group"])?'Einstellungen':'Login').'</a></li>
			'.((($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin"))?'<li><a href="/ant_list.html">Anträge Verwalten</a></li>':'').'
			'.(($_SESSION["user"]["group"] == "admin")?'<li><a href="/usr_men.html">Rechteverwaltung</a></li>':'').'	
			<li><a href="/impressum.html">Impressum</a></li>	
		</ul>';


if ($_GET["load"]){
	if (file_exists("view/".$_GET["load"].".php")){
		include_once "view/".$_GET["load"].".php";
	}elseif (file_exists("text/".$_GET["load"].".php")){
		$_GET["text"] = $_GET["load"];
		include_once "view/text.php";
	}else{
		$_GET["text"] = "404";
		include_once "view/text.php";
	}
}else{
	$_GET["text"] = "home";
	include_once "view/text.php";
}
?>
</html>