<?php 



$kml = KML_load();

?>
  <body onload="init('/main.kml',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		<?=$LOGO; ?>

    <h1 id="title">GeoRIS - BETA - Übersicht</h1>
     
    <div id="map" style="width: 100%; height: 90%; border: 1px solid black;" class="smallmap"></div>

	
  </body>
