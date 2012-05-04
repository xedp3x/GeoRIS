<?php
/*
 * 
 *
 * (A) Mario XEdP3X
 * (c) GPL
 */

if (!(($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin")))
	error("Access Denied","Für diese Seite reichen Ihre rechte nicht aus");


$liste = ANTRAG_list();

?>
<body>
	<?=$LOGO; ?>
	<h1 id="title">Liste aller Anträge</h1>
	<table style="width:  100%">
		<tr><td colspan="2"><center>
			Zum suchen [STRG]+[F] drücken &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type=button onclick="self.location.href='ant_edit.html?reset=true'" value="Neuen Antrag einstellen"/>
			<input type=button onclick="self.location.href='ant_update.html'" value="Updates"/>
		</center><br /><h2></h2></td></tr>
		<?php while( list ( $key, $val ) = each ( $liste ) ){?>
		<tr onclick="self.location.href='/ant_edit.html?edit=<?=$val["ant"]?>';">
			<td  style="min-width:300px;">
				<?=$val["name"]?> <br />
				<br />
				<?=$val["titel"]?><br />
				<br />
				<?=$val["art"]?><br />
				<?=$val["status"]?><br />
				<?=$val["template"]?> - <?=$val["id"]?><br />
			</td>
			<td>
				<div><? $t = explode("\n",$val["text"]); for($i = 0 ; $i < 7; $i++){echo $t[$i]."<br />";};?></div>
			</td>
		</tr>
		<tr><td colspan="2">
			<h2></h2>
		</td></tr>
		<?php }?>
	</table>