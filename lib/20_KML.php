<?php

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
		
		$out = '';
		while( list ( $key2, $val2 ) = each ( $node ) ){
			$out .=  "{$val2["lon"]},{$val2["lat"]} ";	
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