<?
/*
 * Export der GEO Daten im OSM format
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */


KML_GET();


if (!$kml){
	header("HTTP/1.0 500 No Data");
	die('No Data Selected');
}

header('Content-type: application/x-openstreetmap+xml');
header('Content-Disposition: attachment; filename="GeoRIS.osm"');

$in = 0;

$exporter = Array("outer","inner","way");

while( list ( $key_x, $way_type ) = each ( $exporter ) ){
	while( list ( $key, $val ) = each ( $kml[$way_type] ) ){
		$iw++;
		$way .= "<way id='$iw' version='1'>\r\n\t<tag k='type' v='$way_type' />\r\n";
		
		while( list ( $key2, $val2 ) = each ( $val["data"] ) ){
			$in++;
			$node .= "<node id='$in' version='1' lat='{$val2["lat"]}' lon='{$val2["lon"]}' />\r\n";
			$way  .= "\t<nd ref='$in' />\r\n";
		}
		$way .= "</way>\r\n";
	}
}

echo"<?xml version='1.0' encoding='UTF-8'?>\r\n"; ?>
<osm version='0.6' generator='GeoRIS'>
<?=$node?>
<?=$way?>
</osm>