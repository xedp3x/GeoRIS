<?php
/*
 * Backend für Geodaten in der Datenbank
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

function KML_load($ant = false){
	
	$select = "*,
if( 	date_ende <  CURRENT_TIMESTAMP AND
	date_ende != '0000-00-00 00:00:00' AND
	date_ende is not null
	,'ende',
if( 	date_beschluss <= CURRENT_TIMESTAMP AND
	date_beschluss != '0000-00-00 00:00:00' AND
	date_beschluss is not null
	,'beschluss',
if(	date_antrag >= CURRENT_TIMESTAMP
	,'future',
	'antrag'
))) AS state_at";
	

	if ($ant){
		$aset = SQL_select_as_array("antrag", "ant = $ant", $select );
	}else{
		$aset = SQL_select_as_array("antrag", false , $select);
	}
	
	while( list ( $key_x2, $antrag ) = each ( $aset ) ){
		$ant = $antrag["ant"];
		
		$set1 = SQL_select_as_array("gebiet", "ant = $ant","gebiet_id, role");
		while( list ( $key_x, $gebiet ) = each ( $set1 ) ){

			$set2 = SQL_select_as_array("punkt", "gebiet_id = ".$gebiet["gebiet_id"],"lon, lat","sequence");

			$node = array();
			while( list ( $key2, $val2 ) = each ( $set2 ) ){
				$node[] =  $val2;
				if (!$lon_min){
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
			$kml[$gebiet["role"]][] = array_merge($antrag,array("data" =>$node));
		}

	}
	$kml["border"] = array(
			"lon_min" => $lon_min,
			"lon_max" => $lon_max,
			"lat_min" => $lat_min,
			"lat_max" => $lat_max
	);
	return $kml;

}

