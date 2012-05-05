<body>
		<?=$LOGO; ?>
	    <h1 id="title">Rechteverwaltung</h1>  
<?php
if (!($_SESSION["user"]["group"] == "admin"))
	error("Access Denied","Für diese Seite reichen Ihre rechte nicht aus"); 
	
if ($_POST["mail"]){
	USER_set(array(
		"mail" => $_POST["mail"],
		"group"=> "manager"
	));
}

if ($_GET["unset"]){
	USER_set(array(
		"mail" => $_GET["unset"],
		"group"=> "user"
	));
}

?>
<table style="width: 100%">
	<tr>
		<td>E-Mail</td>
		<td>Gruppe</td>
		<td>Standart Template</td>
		<td>Aktion</td>
	</tr>
	<tr>
		<td colspan="4"><h2></h2></td>
	</tr>
	
<?php 
	$set = USER_list();
	while( list ( $key, $val ) = each ( $set ) ){
		$mail[] = $val["mail"];
		?>
		<tr>
			<td><?=$val["mail"]?> <?=($val["pass"]==null?"(unregistriert)":"")?></td>
			<td><?=$val["group"]?></td>
			<td><?=$val["template"]?></td>
			<td><?php if ($val["group"] <> "admin"){?>
					<a href="?unset=<?=urlencode($val["mail"])?>">Zugang Sperren</a>
				<?php }?>
			</td>
		</tr>
	<?php }?>
</table>
<h2></h2>
<table style="width: 100%"><tr><td>
<form method="post" action="?">
	Manager hinzufügen:<br />
	eMail: <input name=mail /> <input type=submit value="senden"/>
</form>
</td><td>
	Nach Änderungen muss sich der Betroffene neu Einloggen.
</td><td>
	Um einen Admin zu ernännen melde dich beim Serverbetreiber.
</td><td>
	<input type=button onclick="self.location.href='mailto:<?=implode(', ',$mail)?>'" value="Rundmail"/>
</td></tr></table>
	


