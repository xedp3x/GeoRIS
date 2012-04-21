<?php 

if (!isset($_SESSION[md5(serialize($_GET))])){
	$_SESSION["buffer"][md5(serialize($_GET))] = KML_by_way(array($_GET));
}

$kml = $_SESSION["buffer"][md5(serialize($_GET))];



?>
  <body onload="init('/buffer.kml?id=<?=md5(serialize($_GET));?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">

    <div id="map" style="width: 100%; height: 95%; border: 1px solid black;" class="smallmap"></div>
	
  </body>
