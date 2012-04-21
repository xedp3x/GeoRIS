<?

KML_GET();


if (!$kml){
	header("HTTP/1.0 500 No Data");
	die('No Data Selected');
}

header('Content-type: application/vnd.google-earth.kml+xml');

echo'<?xml version="1.0" encoding="UTF-8"?>'."\r\n"; ?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
<Document>
	<name>KmlFile</name>
	<open>1</open>
	
	<StyleMap id="msn_ylw-pushpin">
		<Pair>
			<key>normal</key>
			<styleUrl>#sn_ylw-pushpin</styleUrl>
		</Pair>
		<Pair>
			<key>highlight</key>
			<styleUrl>#sh_ylw-pushpin</styleUrl>
		</Pair>
	</StyleMap>
	<StyleMap id="msn_ylw-pushpin0">
		<Pair>
			<key>normal</key>
			<styleUrl>#sn_ylw-pushpin0</styleUrl>
		</Pair>
		<Pair>
			<key>highlight</key>
			<styleUrl>#sh_ylw-pushpin0</styleUrl>
		</Pair>
	</StyleMap>
	
	<Style id="sn_ylw-pushpin">
		<IconStyle>
			<Icon>
				<href>/img/marker.png</href>
			</Icon>
		</IconStyle>
		<LineStyle>
			<color>ff000055</color>
			<width>3</width>
		</LineStyle>
		<PolyStyle>
			<color>7f0000ff</color>
		</PolyStyle>
	</Style>
	<Style id="sn_ylw-pushpin0">
		<IconStyle>
			<Icon>
				<href>/img/marker.png</href>
			</Icon>
		</IconStyle>
		<LineStyle>
			<color>ffff00ff</color>
			<width>3</width>
		</LineStyle>
		<PolyStyle>
			<color>7fff00ff</color>
		</PolyStyle>
	</Style>
	
	0
	<Folder>
		<name>Name</name>
		<open>0</open>
		<description>description</description>


	<?php if ($kml["outer"]){?>

		<Placemark>
			<name>Fläche</name>
			<description><![CDATA[<pre><?php print_r($kml["inner"]); print_r($kml["outer"]);?></pre>]]></description>
			<styleUrl>#msn_ylw-pushpin0</styleUrl>
			<Polygon>
				<innerBoundaryIs>
					<?php while( list ( $key, $val ) = each ( $kml["outer"] ) ){?>
					<LinearRing>
						<coordinates>
							<?=$val["data"]?>
						</coordinates>
					</LinearRing>
					<?php }?>
				</innerBoundaryIs>
				
				<?php if ($kml["inner"]){?>
				<outerBoundaryIs>
					<?php while( list ( $key, $val ) = each ( $kml["inner"] ) ){?>
					<LinearRing>
						<coordinates>
							<?=$val["data"]?>
						</coordinates>
					</LinearRing>
					<?php }?>
				</outerBoundaryIs>
				<?php }?>
			</Polygon>
		</Placemark>
		
		<?php }?>
		
		<?php while( list ( $key, $val ) = each ( $kml["way"] ) ){?>
		<Placemark>
			<name>Strecke</name>
			<description><![CDATA[<pre><?php print_r($val)?></pre>]]></description>
			<styleUrl>#msn_ylw-pushpin</styleUrl>
			<?php if (Count(explode(",",$val["data"]))>2){?>
			<LineString>
					<LinearRing>
						<coordinates>
							<?=$val["data"]?>
						</coordinates>
					</LinearRing>
			</LineString>
			<?php }else{?>
			<Point>
				<coordinates><?=$val["data"]?></coordinates>
			</Point>
			<?php }?>
		</Placemark>
		<?php } // kml["way"]?>
		
		
	</Folder>
</Document>
</kml>
