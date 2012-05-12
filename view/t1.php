<pre><?php 

$text = file_get_contents("/home/mario/allris.html");

$tmp = substr($text,strpos($text,'<div id="allrisContent">'));
$tmp = substr($tmp ,0,strpos($tmp,'<h3>Legende</h3>'));


echo utf8_encode(HTML2TEXT($tmp));