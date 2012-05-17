<?php
/*
 * Bearbeiten der Anträge Daten
 *
 * (A) Mario XEdP3X
 * (c) GPL
 */

if (!(($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin"))){
	?><body><?=$LOGO; ?><h1 id="title">Access Denied</h1><?
	error("Access Denied","Für diese Seite reichen Ihre rechte nicht aus");
}

if ($_GET["delete"]){
	$_GET["reset"] = true;
	ANTRAG_delete($_GET["delete"]);
}

if ($_GET["reset"]){
	$_SESSION['ant'] = false;
	$_SESSION["kml"] = false;
}

if ($_GET["edit"]){
	ANTRAG_load($_GET["edit"]);
}

if ($_POST["create"]){
	$_SESSION["ant"] = ANTRAG_create($_POST["template"], $_POST["id"]);
	$_SESSION["kml"] = false;
}

if ($_GET["reimport"]){
	$_SESSION["ant"] = ANTRAG_create($_SESSION["ant"]["template"],$_SESSION["ant"]["id"],$_SESSION["ant"]);
}

if ($_GET["renew"]){
	$id 	= $_SESSION["check"]["site"]["old"][$_GET["renew"]]["ant"];
	$_SESSION["ant"] = $_SESSION["check"]["site"]["new"][$_GET["renew"]];
	$_SESSION["ant"]["ant"] 	= $id;
	$_GET["auto"] = true;
}

if (isset($_POST["name"])){
	$_SESSION["ant"] = $_POST;
}

?>
<body onload=" 
			myCal  = new Calendar({ date:  'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
			myCal1 = new Calendar({ date1: 'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
			myCal2 = new Calendar({ date2: 'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
			myCal3 = new Calendar({ date3: 'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
			">
	<?=$LOGO; ?>
	<h1 id="title">Anträge bearbeiten</h1>
	<?php
	if (!$_SESSION["ant"]){?>
		<form method="post" action="?<?=($_GET["auto"]?"auto=true":"")?>">
			Neue Antrag einstellen:<br />
			Template: 
			<select name='template'><?
				$set = $_templates;
		
				while( list ( $key, $val ) = each ( $set ) ){
					if ($_SESSION["user"]["default"]["template"] == $val["id"]){
						echo "<option selected='selected' value='{$val["id"]}'>{$val["name"]}</option>\n";
					}else{
						echo "<option value='{$val["id"]}'>{$val["name"]}</option>\n";
					}
				}?>
			</select><br />
			ID: <input name=id /><br />
			<input type=button onclick="self.location.href='/ant_list.html'" value="Abbrechen"/>
			<input type=submit name="create" value="Weiter"/>
		</form>
	<?php }elseif($_GET["save"]){
		ANTRAG_save($_SESSION["ant"],$_SESSION["kml"]);
		if ($_GET["auto"]){?>
		<script type="text/javascript">
			self.location.href='/ant_update.html';
		</script>
		<?php }else{?>
		<script type="text/javascript">
			self.location.href='/ant_list.html';
		</script>
	<?php }
		}else{?>
		<div style="float:left; width:50%;">
		<form method="post" action="?<?=($_GET["auto"]?"auto=true":"")?>">
			<table style="width: 100%;">
			<tr>
				<td>Name</td>
				<td><input name="name" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["name"]?>"/></td>
			</tr>
			<tr>
				<td>Titel</td>
				<td><input name="titel" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["titel"]?>"/></td>
			</tr>
			<tr>
				<td>Fortschritt</td>
				<td><table><tr>
					<td>Antragsstellung</td>
					<td>Beschluss</td>
					<td>Abgeschlossen</td>
					<td></td>
					<td>Nächster Termin</td>
				</tr><tr>
					<td><input id="date1" name="date_antrag" type="text" 		value="<?=(substr($_SESSION["ant"]["date_antrag"],0,10)<>'0000-00-00'?substr($_SESSION["ant"]["date_antrag"],0,10):'')?>"/></td>
					<td><input id="date2" name="date_beschluss" type="text" 	value="<?=(substr($_SESSION["ant"]["date_beschluss"],0,10)<>'0000-00-00'?substr($_SESSION["ant"]["date_beschluss"],0,10):'')?>"/></td>
					<td><input id="date3" name="date_ende" type="text" 			value="<?=(substr($_SESSION["ant"]["date_ende"],0,10)<>'0000-00-00'?substr($_SESSION["ant"]["date_ende"],0,10):'')?>"/></td>
					<td style="width: 50px;"></td>
					<td><input id="date" name="date" size="22" 					value="<?=(substr($_SESSION["ant"]["date"],0,10)<>'0000-00-00'?substr($_SESSION["ant"]["date"],0,10):'')?>"/></td>
				</tr></table></td>
			</tr>
			<tr>
				<td>Status</td>
				<td><input name="sta
				tus" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["status"]?>"/></td>
			</tr>
			<tr>
				<td>Art</td>
				<td><input name="art" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["art"]?>"/></td>
			</tr>
			<tr>
				<td>URL</td>
				<td><input name="url" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["url"]?>"/></td>
			</tr>
			<tr>
				<td>Pad</td>
				<td><input name="pad" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["pad"]?>"/></td>
			</tr>
			<tr>
				<td>Wiki</td>
				<td><input name="wiki" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["wiki"]?>"/></td>
			</tr>
			<tr>
				<td>Forum</td>
				<td><input name="forum" style="width: 100%;" maxlength="200" value="<?=$_SESSION["ant"]["forum"]?>"/></td>
			</tr>
			<tr>
				<td>Text</td>
				<td><textarea name="text" style="width: 100%;" rows="15"><?=$_SESSION["ant"]["text"]?></textarea></td>
			</tr>
			<tr>
				<td>ID</td>
				<td>
					<input name="ant" size=10 readonly=true value="<?=$_SESSION["ant"]["ant"]?>"/>
					<input name="id" size=10 readonly=true value="<?=$_SESSION["ant"]["id"]?>"/>
					<input name="version" size=40 readonly=true value="<?=$_SESSION["ant"]["version"]?>" style="display: none"/>
					<input name="template" readonly=true value="<?=$_SESSION["ant"]["template"]?>"/>
				</td>
			</tr>
			</table>
			<center>
				<input type=submit value="Übernehmen" />
				<input type=button onclick="self.location.href='?reimport=true'" value="Reimport"/> 
			</center>
		</form>	
		<h2></h2>
				<input type=button onclick="self.location.href='/ant_list.html'" value="Abbrechen"/>
			<? if($_SESSION["kml"]){?>
				<input type=button onclick="self.location.href='/geo_edit.html'" value="Orte Bearbeiten"/>
			<?php }else{?>
				<input type=button onclick="self.location.href='/such.html'" value="Ort Suchen"/>
			<?php }?>
				<input type=button onclick="self.location.href='?save=true<?=($_GET["auto"]?"&auto=true":"")?>'" value="Speichern"/>
				<input type=button onclick="if (confirm('Wollen Sie diesen Antrag wirklich Löschen?')){self.location.href='?delete=<?=$_SESSION["ant"]["ant"]?>'}" value="Löschen"/>
		</div><div>
			  <iframe style="width:50%;height:90%" name=iframe id=iframe src="show.html?req=session"></iframe>
		</div>
	<?php }?>
</body>