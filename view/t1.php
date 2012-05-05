<?php 

$text = file_get_contents("/home/mario/allris.text");

$tmp = substr($text, strpos($text," ******"));
$tmp = substr($tmp , strpos($tmp ,'Anlagen:'));
$tmp = substr($tmp,0,strpos($tmp ,'=============='));

$tmp = explode("                 ",$tmp);

$date = 0;
while( list ( $key, $val ) = each ( $tmp ) ){
	$datum = substr($val,0,10);
	if (preg_match('/^(\d{2}).(\d{2}).(\d{4})$/', $datum, $wert)){
		$x = strtotime($datum);
		if ($x){
			if ($x > $date)
				$date = $x; 
		}
	}
}
