<?php 



$kml = KML_by_way(array_merge(
	OSM_find_all('Voigtstraße'),
	OSM_find_all('103Bar'),
	OSM_find_all('Deutsche Rentenversicherung')
	));

$_SESSION['kml'] = $kml;




?>
  <body onload="init('/session.kml?uni=<?=time();?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		<?=$LOGO; ?>

    <h1 id="title">GeoRIS Test "t2"</h1>
     
    <div id="map" style="width: 100%; height: 80%; border: 1px solid black;" class="smallmap"></div>
    <textarea id="output"></textarea>
	
  </body>
