<?php 
/*
 * Anzeigen bestimter GEO Daten
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */


if ($_GET["way"]){
	if (!isset($_SESSION[md5(serialize($_GET))])){
		$_SESSION["buffer"][md5(serialize($_GET))] = KML_by_way(array($_GET));
	}
	
	$kml = $_SESSION["buffer"][md5(serialize($_GET))];
	
	
	
	?>
	  <body onload="init('/buffer.kml?id=<?=md5(serialize($_GET));?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
	
	    <div id="map" style="width: 100%; height: 95%; border: 1px solid black;" class="smallmap"></div>
		
	  </body>
<?php }elseif($_GET["kml"]){

	$kml["way"][$_GET["id"]] = $_SESSION["kml"][$_GET["kml"]][$_GET["id"]];
	
	$kml = KML_border($kml);
	
	$_SESSION["buffer"]["geo_edit"] = $kml;
	
	
	
	?>
	  <body onload="init('/buffer.kml?id=geo_edit&uni=<?=time();?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
	
	    <div id="map" style="width: 100%; height: 95%; border: 1px solid black;" class="smallmap"></div>
		
	  </body>
<?php }else{
	KML_GET();
	
	if ($kml){
	?>
		  <body onload="init('/session.kml?uni=<?=time();?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		
		    <div id="map" style="width: 100%; height: 95%; border: 1px solid black;" class="smallmap"></div>
			
		  </body>	
<?php }else{?>
	<body>
		<center>
			<img src="/img/sorry.png" /><br />
			Keine Orte
		</center>
	</body>
	<?php }
}