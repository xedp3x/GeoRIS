<?php
/*
 * Bearbeiten der GEO Daten
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */

if (!(($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin")))
	error("Access Denied","Für diese Seite reichen Ihre rechte nicht aus");

if ($_POST["reset"]){
	$_SESSION['kml'] = false;
}

$kml = $_SESSION['kml'];

if ($_POST["map_edit"]){
	$kml = KML_merge($kml, KML_form_json($_POST["map_edit"]));	
}


if ($_POST["kml_role"]){
	$kml_ = $kml;
	$kml = array();
	while( list ( $old, $val_x1 ) = each ( $_POST["kml_role"] ) ){
		while( list ( $id, $new ) = each ( $val_x1 ) ){
			$kml[$new][] = $kml_[$old][$id];
		}
	}
}

if ($_GET["del"]){
	unset($kml[$_GET["del"]][$_GET["id"]]);
}

if (is_array($kml))
	$kml = KML_border($kml);

$_SESSION['kml'] = $kml;
    
if ($_SESSION["user"]["geolocat"]){
	$border = array(
		"lon_min" => $_SESSION["user"]["geolocat"]["lon"] - 0.1,
		"lon_max" => $_SESSION["user"]["geolocat"]["lon"] + 0.1,
		"lat_min" => $_SESSION["user"]["geolocat"]["lat"] - 0.1,
		"lat_max" => $_SESSION["user"]["geolocat"]["lat"] + 0.1
	);
}else{
	$border = array(
		"lon_min" => 13.3,
		"lon_max" => 13.5,
		"lat_min" => 52.4,
		"lat_max" => 52.6,
	);	
}

switch ($_GET["show"]){
	case "add":?>
	<body onload="init_edit('<?=($kml?"/session.kml?uni=".time():"");?>',<?=$border["lon_min"]?> , <?=$border["lat_min"]?> , <?=$border["lon_max"]?> , <?=$border["lat_max"]?> )">
		<?=$LOGO; ?>
	    <h1 id="title">Orte bearbeiten</h1>  
		
		<div id="map" style="width: 100%; height: 85%; border: 1px solid black;" class="smallmap"></div>
	    <form method="post" action="geo_edit.html">
		    <div id="map_edit" style="display: none;" >
		    
		    </div>
		    <input type="submit" onclick="get_map();" value="weiter" />
		 </form>
		<?php break;
	
		break;
		case "finisch":?>
		<body onload="init('<?=($kml?"/session.kml?uni=".time():"");?>',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
		<?=$LOGO; ?>
	    <h1 id="title">Orte bearbeiten</h1>  
		
		<div id="map" style="width: 100%; height: 85%; border: 1px solid black;" class="smallmap"></div>
		<center>
			<input type=button onclick="self.location.href='/geo_edit.html'" value="Zurück"/>
			<input type=button onclick="self.location.href='/ant_edit.html'" value="Fertig"/>
		</center>
		<?php 
		break;
	case "main":
	default:?>
		<body>
			<?=$LOGO; ?>
		    <h1 id="title">Orte bearbeiten</h1> <?php 
		
			if (!$kml){
				echo "Es wurden noch keine Orte Markiert<br /><br />
				<input type=button onclick=\"self.location.href='/geo_edit.html?show=add'\" value=\"Mehr markieren\"/>";
			}else{?>
				<div style="float:left; width:50%;">
				<form method="post">
					<table>
						<tr>
							<th style="text-align: center">Typ</th>
							<th style="text-align: center">Punkte</th>
							<th style="text-align: center">Aktion</th>
						</tr>
						<?php
						$set = $kml;
						$typen = array("way","inner","outer");
						while( list ( $key_x, $type ) = each ( $typen ) ){
							while( list ( $key, $val ) = each ( $set[$type] ) ){?>
						<tr>
							<td>
								<select name="kml_role[<?=$type?>][<?=$key?>]" size="1" onchange="this.form.submit();">
								<?if (count($val["data"]) > 1){?>
								      <option <?=($type=="way"?"selected":"")  ?> value="way"  >Weg</option>
								      <option <?=($type=="outer"?"selected":"")?> value="outer">Fläche</option>
								      <option <?=($type=="inner"?"selected":"")?> value="inner">Frei</option>
								<?php }else{?>
								      <option value="way">Punkt</option>
								 <?php }?>
								</select>
							</td>
							<td style="text-align: center"><?=count($val["data"])?></td>
							<td>
								 <a href="show.html?kml=<?=$type?>&id=<?=$key?>" target="iframe">Zeigen</a>
								 <a href="geo_edit.html?del=<?=$type?>&id=<?=$key?>">Löschen</a>
							</td>
						</tr>
						<?php }}?>
					</table>
					<br />
					<input type=button onclick="self.location.href='/geo_edit.html?show=add'" value="Mehr Orte"/>
					<input type=button onclick="self.location.href='/api.php?modul=osm&req=session'" value="Download"/>
					<input type=button onclick="self.location.href='/geo_edit.html?show=finisch'" value="Weiter"/>
					
				</form>
				</div><div>
			    	<iframe style="width:50%;height:90%" name=iframe id=iframe src="main.php?load=text&text=tux_map"></iframe>
			    </div>
			<?php }?>
		</body>
		<?
}
