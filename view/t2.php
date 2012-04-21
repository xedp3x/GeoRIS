<?php 



$kml = KML_by_way(array_merge(
	OSM_find_all('Voigtstraße'),
	OSM_find_all('103Bar'),
	OSM_find_all('Deutsche Rentenversicherung')
	));

$_SESSION['kml'] = $kml;

if ($_GET["map_edit"]){
	echo "<form><input value='zurück' type=submit /></form>";
	
	$_SESSION['kml'] = KML_form_json($_GET["map_edit"]);
	
	DUMP(KML_form_json($_GET["map_edit"]));

}


?>
  <body onload="init_edit('/session.kml?uni=<?=time();?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		<?=$LOGO; ?>

    <h1 id="title">GeoRIS Test "t2"</h1>
     
    <div id="map" style="width: 100%; height: 90%; border: 1px solid black;" class="smallmap"></div>
    <form method="post">
	    <div id="map_edit" style="display: none;" >
	    
	    </div>
	    <input type="submit" onclick="get_map();" value="weiter" />
	 </form>
	
  </body>
