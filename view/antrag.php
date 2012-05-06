<?php 
 $data = ANTRAG_get($_GET["ref"]);
 $val = $data;
 KML_GET($_GET["ref"]);
 
?>
<body onload="init('',<?=$kml["border"]["lon_min"]?> , <?=$kml["border"]["lat_min"]?> , <?=$kml["border"]["lon_max"]?> , <?=$kml["border"]["lat_max"]?> )">
	<?=$LOGO; ?>
	<h1 id="title"><?=$data["titel"]?></h1>
	
	<input type=hidden id="no_click" />
	
	<div style="width: 45%; float:left;">

	<div style="width: 98%">
		<h2><?=$data["name"]?></h2>
		<?=str_replace("\n","<br />",$val["text"])?>
		<h2></h2>
		<?php if ($val["url"]){?><a href="<?=$val["url"]?>" target="_blank" style="text-decoration:none; color: #003a6b;">Weiter lesen...</a><?php }?>
		<?php if ($val["pad"]){?><a href="<?=$val["pad"]?>" target="_blank" style="text-decoration:none; color: #003a6b;">Pad</a><?php }?>
		<?php if ($val["wiki"]){?><a href="<?=$val["wiki"]?>" target="_blank" style="text-decoration:none; color: #003a6b;">Wiki</a><?php }?>
		<?php if ($val["forum"]){?><a href="<?=$val["forum"]?>" target="_blank" style="text-decoration:none; color: #003a6b;">Forum</a><?php }?>
	</div>
	
	</div>
	<div id="map" style="width: 50%; height: 90%; border: 1px solid black; float:left;" class="smallmap"></div>
	
</body>