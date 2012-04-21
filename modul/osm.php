<?

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
		$way .= "<way id='{$val["way"]}' version='1'>\r\n\t<tag k='type' v='$way_type' />\r\n";
		$set = explode(" ",$val["data"]);
		while( list ( $key2, $val2 ) = each ( $set ) ){
			$in++;
			$x = explode(",",$val2);
			if (count($x) == 2){
				$node .= "<node id='$in' version='1' lat='{$x[1]}' lon='{$x[0]}' />\r\n";
				$way  .= "\t<nd ref='$in' />\r\n";
			}	
		}
		$way .= "</way>\r\n";
		
	}
}


echo"<?xml version='1.0' encoding='UTF-8'?>\r\n"; ?>
<osm version='0.6' generator='GeoRIS'>
<?=$node?>
<?=$way?>
</osm>
