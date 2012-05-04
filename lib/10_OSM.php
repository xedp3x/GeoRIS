<?php

/*
 * Backend für OSM Datenbak
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */

function OSM_by_way($id){
	$req = '[out:json];way('.$id.');out meta; node(w);out;';
	
	$json = file_get_contents("http://overpass-api.de/api/interpreter?data=".urlencode($req));
	$data = json_decode($json,true);
	
	$set1 = $data;
	while( list ( $key, $val ) = each ( $set1["elements"] ) ){
		if ($val["type"] == "node"){
			$node[$val["id"]] = $val;		
		}
		if ($val["type"] == "way"){
			$way = $val;		
		}
		
	}
	while( list ( $key, $val ) = each ( $way["nodes"] ) ){
		$out[] = $node[$val];
	}
	return $out;
}

function OSM_by_node($id){
	$req = '[out:json];node('.$id.');out;';
	
	$json = file_get_contents("http://overpass-api.de/api/interpreter?data=".urlencode($req));
	$data = json_decode($json,true);
	
	return $data["elements"];
}


function OSM_find_all($name){
	return SQL_select_as_array('osm',"name like '%$name%'","*","name");
	
}