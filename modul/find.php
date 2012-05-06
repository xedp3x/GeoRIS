<?php

$set = ANTRAG_bygeo($_GET["lat"],$_GET["lon"],1);

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
			)?>_<?=$val["ant"]?>.html';">
			<td><?=$val["name"]?></td>
			<td>    </td>
			<td><?=$val["titel"]?></td>
		</tr>
	<?php }?>
	</table>
<?php }else{
	header("HTTP/1.0 404 Not Found");
}