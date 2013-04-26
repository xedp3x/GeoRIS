<?php

$set = ANTRAG_bygeo($_GET["lat"],$_GET["lon"],($_GET["resolution"]/20));

if (count($set) > 0){
	//DUMP($set);?>
	<table style="width: 100%">
	
	<?php while( list ( $key, $val ) = each ( $set ) )
	{
		if ($lala){?>
		<tr><td colspan="3"><h2></h2></td></tr>
		<?php }else{ $lala = true; }?>
		<tr onclick="self.location.href='/<?=urlencode(
			preg_replace("/[^a-zA-Z0-9äöüÄÖÜß():]/", "-", 
			$val["titel"])
			)?>_<?=$val["ant"]?>.html';"
			style="cursor: hand; cursor: pointer;">
			<td><?=$val["name"]?></td>
			<td>    </td>
			<td><?=$val["titel"]?></td>
		</tr>
	<?php }?>
		<tr><td colspan="3"><h2></h2></td></tr>
		<tr onclick="self.location.href='/next.html?lat=<?=$_GET["lat"]?>&lon=<?=$_GET["lon"]?>';"
			style="cursor: hand; cursor: pointer;">
			<td>Mehr anzeigen</td>
			<td>    </td>
			<td></td>
		</tr>
	</table>
<?php }else{
	header("HTTP/1.0 404 Not Found");
}