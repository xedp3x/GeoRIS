<?php 
/*
 * Laden der GEO Daten aus der OSM DB
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */

if ($_GET["use"]){
	$set = $_GET["use"];
		
	while( list ( $key, $val ) = each ( $set ) ){
		$such[] = array(
			"way" => $val,
			"role"=> $_GET["as"][$val]
		);
	}
		
	$_SESSION["ergebnis"] = $such;
	$kml = KML_by_way($such);
	
	$_SESSION['kml'] = $kml;
	
	?>
  <body onload="init('/session.kml?uni=<?=time();?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		<?=$LOGO; ?>

    <h1 id="title">Zusammenfassung:</h1>
     
    <div id="map" style="width: 100%; height: 85%; border: 1px solid black;" class="smallmap"></div>
	<center>
		<input type=button onclick="window.history.back()" value="Zurück"/>
		<input type=button onclick="self.location.href='/api.php?modul=osm&req=session'" value="Download"/>
		<input type=button onclick="self.location.href='/geo_edit.html'" value="Weiter"/>
	</center>
  </body>
	
<?php
}elseif ($_GET["such"]){?>
	<body>
		<?=$LOGO; ?>
	    <h1 id="title">Ort markieren</h1>
	    <div style="float:left; width:50%;">
	    <h2>Gefundene Orte:</h2>
	    <form>
	    	<table>
	    	<tr>
				<td>
					<center>Art</center>
				</td>
				<td>
					<center>ID</center>
				</td>
				<td>
					Name
				</td>
				</tr>
	    	<?php
			$treffer = OSM_find_all($_GET["such"]);
			
			while( list ( $key, $val ) = each ( $treffer ) ){
				?>
					<tr>
						<td>
							<select name="as[<?=$val["way"]?>]" size="1">
							 <?if ($val["way"] > 0){?>
						      <option <?=($val["role"]=="way"?"selected":"")  ?> value="way"  >Weg</option>
						      <option <?=($val["role"]=="outer"?"selected":"")?> value="outer">Fläche</option>
						      <option <?=($val["role"]=="inner"?"selected":"")?> value="inner">Frei</option>
						     <?php }else{?>
						      <option value="way">Punkt</option>
						     <?php }?>
						    </select>
						</td><td>
						    <?=$val["way"]?>
						</td>
						<td>
							 <input type="checkbox" name="use[]" value="<?=$val["way"]?>">
							 <a href="show.html?way=<?=$val["way"]?>&role=way" target="iframe"><?=$val["name"]?></a>
						</td>
					</tr>
				
				<?php  
			}?>
	    	</table>
	    	<h2></h2>
	    	<input type=button onclick="self.location.href='such.html'" value="zurück"/>
	    	<input type=submit value="weiter" />
	    </form>
	    </div><div>
	    	<iframe style="width:50%;height:90%" name=iframe id=iframe src="main.php?load=text&text=tux_map"></iframe>
	    </div>
	</body>
<?php }else{?>
<body>
	<?=$LOGO; ?>
    <h1 id="title">Ort markieren</h1>
	    <form>
	    	<p>Karte durchsuchen</p>
	    	<input name=such /><br />
	    	<input type=submit value="Suchen" />
	    </form>
	    
	    <h2></h2>
	    
	    <form>
	    	Orte Hochladen<br />
	    	<input type=file disabled /><br />
	    	<input type=submit value="Hochladen" disabled />
	    </form>
	    <p>
		    Geht noch nicht :(
	    </p>
	    
	    <h2></h2>
	    
	    <form action="geo_edit.html" method="post">
	    	Suche überspringen<br />
	    	<input type="hidden" name="reset" value=true />
	    	<input type=submit value="Weiter" />
	    </form>
</body>
	
<?}