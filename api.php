<?php
include '_lib.php';

if (file_exists("modul/".$_GET["modul"].".php")){
	include_once "modul/".$_GET["modul"].".php";
}else{
	header("HTTP/1.0 404 Not Found");?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Modul Not Found</h1>
<p>The requested URL was not found on this server.</p>
<pre><?php print_r($_GET);?></pre>
</body></html>
<?php }