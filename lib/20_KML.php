<?php
/*
 * Backend für Geodaten
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */



function KML_by_way($list){
	$set = $list;
	while( list ( $key, $val ) = each ( $set ) ){
		if ($val["way"] < 0){
			$node = OSM_by_node(0-$val["way"]);
		}else{
			$node = OSM_by_way($val["way"]);
		}
		if (!$lon_min){
			$lon_min = $node[0]["lon"];
			$lon_max = $node[0]["lon"];
			$lat_min = $node[0]["lat"];
			$lat_max = $node[0]["lat"];
		}
		
		$out = array();
		while( list ( $key2, $val2 ) = each ( $node ) ){
			$out[] =  $val2;	
			if ($val2["lon"] < $lon_min) $lon_min = $val2["lon"];
			if ($val2["lon"] > $lon_max) $lon_max = $val2["lon"];
			if ($val2["lat"] < $lat_min) $lat_min = $val2["lat"];
			if ($val2["lat"] > $lat_max) $lat_max = $val2["lat"];
			
		} 
		$kml[$val["role"]][] = array_merge($val,array("data" =>$out));
	}
	
	$kml["border"] = array(
			"lon_min" => $lon_min,
			"lon_max" => $lon_max,
			"lat_min" => $lat_min,
			"lat_max" => $lat_max
	);
	return $kml;	
}

function KML_border($in){
	$set = $in;
	while( list ( $key, $val ) = each ( $set ) ){ // way
		while( list ( $key3, $val3 ) = each ( $val ) ){// list of way
			if (is_array($val3["data"])){
				while( list ( $key2, $val2 ) = each ( $val3["data"] ) ){ // nodes
					if (!isset($lon_min)){
						$lon_min = $val2["lon"];
						$lon_max = $val2["lon"];
						$lat_min = $val2["lat"];
						$lat_max = $val2["lat"];
					}
					if ($val2["lon"] < $lon_min) $lon_min = $val2["lon"];
					if ($val2["lon"] > $lon_max) $lon_max = $val2["lon"];
					if ($val2["lat"] < $lat_min) $lat_min = $val2["lat"];
					if ($val2["lat"] > $lat_max) $lat_max = $val2["lat"];
				} 
			}
		}
	}
	$out = $in;
	$out["border"] = array(
			"lon_min" => $lon_min,
			"lon_max" => $lon_max,
			"lat_min" => $lat_min,
			"lat_max" => $lat_max
	);
	
	return $out;
}

function KML_GET(){
	global $_GET;
	global $kml;
	global $_SESSION;
	
	switch ($_GET["req"]){
		case "session":
			$kml = $_SESSION['kml'];
			break;
			
		case "find":
			$kml = KML_by_way($_GET["find"]);
			break;
			
		case "buffer":
			$kml = $_SESSION["buffer"][$_GET["id"]];
			break;
			
		Default:
			$kml = false;		
	}
}

function KML_merge($a, $b){
	if (!$a) return $b;
	if (!$b) return $a;
	
	$set = array_merge($a,$b);
	while( list ( $key, $val ) = each($set)){
		if (is_array($a[$key]) AND is_array($b[$key])){
			$out[$key] = array_merge($a[$key],$b[$key]);	
		}elseif(is_array($a[$key])){
			$out[$key] = $a[$key];
		}elseif(is_array($b[$key])){
			$out[$key] = $b[$key];
		}
	}
	return $out;
}

function KML_form_json($array){
	if (!is_array($array)) 	
		$array = array($array);
	
	while( list ( $key_x, $json ) = each($array)){
		$data = json_decode($json,true);
		$data = $data["geometry"];
		
		switch ($data["type"]){
			case "Polygon": 	$type = "outer"; break;
			case "LineString": 	$type = "way"; $data["coordinates"] = array($data["coordinates"]);break;
			case "Point": 		$type = "way"; $data["coordinates"] = array(array($data["coordinates"]));break;
			default: error("JSON","Import des JSON nicht möglich. Type '".$data["type"]."' ist unbekannt!");
			
		}
		
		$set = $data["coordinates"];
		
		$out = array(
			"role" => $type
		);
		
		while( list ( $key2, $val2 ) = each ( $set ) ){
			while( list ( $key, $val ) = each ( $val2 ) ){
				$out["data"][] = array(
					"lon" => $val[0],
					"lat" => $val[1]
				); 
			}
			$kml[$type][] = $out;
		}
	}
	return $kml;
	
}