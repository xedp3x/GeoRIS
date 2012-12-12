<?php
/*
 * API für zugriffe auf die Datenbank
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */


if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}

$verbindung = mysql_connect($config["mysql"]["host"],$config["mysql"]["username"],$config["mysql"]["password"])
	or error("xD001-sql_6",mysql_error());
mysql_select_db($config["mysql"]["database"])
	or error("xD001-sql_9",mysql_error());
mysql_query('set character set utf8;')
	or error("xD001-sql_9",mysql_error());	
	
	
function SQL_query($abfrage){
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_23",mysql_error());
	return $ergebnis;
}		
	
function SQL_query_as_array($abfrage){
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_23",mysql_error());
	$i = 0;
	while($row  = mysql_fetch_array($ergebnis))
	{
		while( list ( $key, $val ) = each ( $row ) )
		{
			if (! is_int($key) ){
				$out[$i][$key] = $val;
			}
		}
		$i++;
	};
	return $out;
}	
	
function SQL_select_as_array($tabel, $where = false , $sparlte = "*", $order = False, $group = false){
	$abfrage	= "SELECT $sparlte FROM $tabel";
	if ($where){
	   $abfrage = $abfrage." WHERE $where";}
	if ($order){
	   $abfrage = $abfrage." ORDER BY $order";}
	if ($group){
	   $abfrage = $abfrage." GROUP BY $group";}	   
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_23",mysql_error());
	$i = 0;
	while($row  = mysql_fetch_array($ergebnis))
	{
		while( list ( $key, $val ) = each ( $row ) )
		{
			if (! is_int($key) ){
				$out[$i][$key] = $val;
			}
		}
		$i++;
	};
	return $out;	
}
function SQL_select_one($tabel, $where = false , $sparlte = "*", $order = ""){
	$abfrage	= "SELECT $sparlte FROM $tabel";
	if ($where){$abfrage = $abfrage." WHERE $where";}
	$abfrage = $abfrage." $order LIMIT 1";
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_43",mysql_error());
	$set = mysql_fetch_array($ergebnis);
	while( list ( $key, $val ) = each ( $set ) )
	{
		if (! is_int($key) ){
			$out[$key] = $val;
		}
	}
	return $out;	
}
function SQL_update($tabel, $where, $to){
	$abfrage	= "UPDATE $tabel SET $to WHERE $where";
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_57",mysql_error());
	return $ergebnis;	
}
function SQL_insert($tabel, $insert){
	$set = $insert;
	$abfrage = ""; 	
	while( list ( $key, $val ) = each ( $set ) )
	{
		if ( empty ($abfrage))
		{				
			if(is_int($val) or (substr($val, 0, 7) == 'ENCRYPT'))
			{
				$abfrage = "`$key` = $val";
			}else{
				$abfrage = "`$key` = '".mysql_real_escape_string($val)."'";
			}
		}else{
			if(is_int($val) or (substr($val, 0, 7) == 'ENCRYPT'))
			{
				$abfrage = $abfrage.", `$key` = $val";
			}else{
				$abfrage = $abfrage.", `$key` = '".mysql_real_escape_string($val)."'";
			}
		} 
	}
	
	$abfrage = "INSERT INTO `$tabel` SET ".$abfrage;
	mysql_query($abfrage)
		or error("xD001-sql_85",mysql_error());
	return mysql_insert_id();	
}
function SQL_insert_update($tabel, $insert){
	$set = $insert;
	$abfrage = ""; 	
	while( list ( $key, $val ) = each ( $set ) )
	{
		if ( empty ($abfrage))
		{				
			if(is_int($val) or (substr($val, 0, 7) == 'ENCRYPT'))
			{
				$abfrage = "`$key` = $val";
			}else{
				$abfrage = "`$key` = '".mysql_real_escape_string($val)."'";
			}
		}else{
			if(is_int($val) or (substr($val, 0, 7) == 'ENCRYPT'))
			{
				$abfrage = $abfrage.", `$key` = $val";
			}else{
				$abfrage = $abfrage.", `$key` = '".mysql_real_escape_string($val)."'";
			}
		} 
	}
	
	$abfrage = "INSERT INTO `$tabel` SET ".$abfrage." ON DUPLICATE KEY UPDATE ".$abfrage.";";
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_113",mysql_error());
	return $ergebnis;	
}
function SQL_delete($tabel, $where){
	if ( !isset($where)){
		error("xD001_121","DELETE Aufruf ohne WHER nicht erlaubt");
	}
	$abfrage	= "DELETE FROM $tabel WHERE $where";
	$ergebnis 	= mysql_query($abfrage)
		or error("xD001-sql_125",mysql_error());
	return $ergebnisw;
	
}