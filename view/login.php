<?php 
if ($_SESSION["user"]["geolocat"]){
	$border = array(
		"lon_min" => $_SESSION["user"]["geolocat"]["lon"] - 0.01,
		"lon_max" => $_SESSION["user"]["geolocat"]["lon"] + 0.01,
		"lat_min" => $_SESSION["user"]["geolocat"]["lat"] - 0.01,
		"lat_max" => $_SESSION["user"]["geolocat"]["lat"] + 0.01
	);
}else{
	$border = array(
		"lon_min" => 13.3,
		"lon_max" => 13.5,
		"lat_min" => 52.4,
		"lat_max" => 52.6,
	);	
}

if ($_GET["logout"]){
	$_SESSION = Array();
	?>
	<body onload="self.location.href='?'">
		<?=$LOGO; ?>
		<h1 id="title">Benutzer</h1>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="90%"><tr><td align="center" valign="middle"><center>
<?php }elseif ($_POST["login"]){
	if (!USER_login($_POST["email"],$_POST["pass"])){?>
	<body>
		<?=$LOGO; ?>
		<h1 id="title">Benutzer</h1>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="90%"><tr><td align="center" valign="middle"><center>
	<big>Login fehlgeschlagen!</big><br /><br /><br />
<?php }else{?>
	<body onload="self.location.href='?'">
		<?=$LOGO; ?>
		<h1 id="title">Benutzer</h1>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="90%"><tr><td align="center" valign="middle"><center>
<?php }
}elseif (!$_SESSION["user"]["group"]){?>
<body>
	<?=$LOGO; ?>
	<h1 id="title">Benutzer</h1>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="90%"><tr><td align="center" valign="middle"><center>
<?php }else{?>
<body onload="init('',<?=$border["lon_min"]?> , <?=$border["lat_min"]?> , <?=$border["lon_max"]?> , <?=$border["lat_max"]?> )">
	<?=$LOGO; ?>
	<h1 id="title">Benutzer</h1>
<?php }

if ($_GET["c"]){
	if ($_GET["c"] == md5($config["sec"]["mail"].$_GET["e"])){
		if ($_POST["pass"]){
			$user = array(
				"mail" => $_GET["e"],
				"pass" => md5($_POST["pass"])
			);
			
			if ($_SESSION["user"]["geolocat"]){
				$user["lat"] = $_SESSION["user"]["geolocat"]["lat"];
				$user["lon"] = $_SESSION["user"]["geolocat"]["lon"];
			}
			
			USER_set($user);
			?>
			Ihr Account wurde erfolgreich erstellt.
		<?php }else{?>
		<form method="post" onsubmit="
				if(document.getElementById('p1').value.length < 5){alert('Bitte vergeben Sie ein längeres Password.'); return false;};
				if(document.getElementById('p1').value != document.getElementById('p2').value){alert('Die Wiederholung stümmt nicht mit Ihrem Passwort überein.'); return false;};">
			<table>
				<tr>
					<td>Password:</td>
					<td><input type=password name=pass id=p1 /></td>
				</tr>
				<tr>
					<td>Wiederholung:</td>
					<td><input type=password id=p2 /></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type=submit value="Fertig" />
					</td>
				</tr>
			</table>
		</form>		
	<?php }
		}else{?>
		<div>
			Der Link ist etwa unvolständig oder abgelaufen.<br /><br />
			Bitte lassen Sie sich eine neuen zuschicken.
		</div>
	<?php }
}elseif (!$_SESSION["user"]["group"]){
	if ($_GET["registrieren"]){
		if ($_POST["email"]){?>
			<div>
				Sie erhalten gleich eine E-Mail.
			</div>
		<?php
		$nachricht = "Wilkommen beim GeoRIS-Online Portal.

Um Ihre Mailadresse zu bestätigen öffnen Sie bitte folgenden Link:

http://piratenradar.de/login.html?c=".md5($config["sec"]["mail"].$_POST["email"])."&e=".urlencode($_POST["email"])."

Wenn diese Anfrage nicht von Ihnen kam können Sie diese Mail einfach Löschen.

Mit freundlichen Grüßen,
Ihr GeoRIS Team.";

		
		
		$header = 'From: piratenradar@online.de' . "\r\n" .
    	'Reply-To: piratenradar@online.de' . "\r\n" .
		'Content-Type: text/plain; charset=utf-8'. "\r\n" .
    	'X-Mailer: PHP/' . phpversion();
		
		$nachricht = wordwrap($nachricht, 70);
		mail($_POST["email"], 'Wilkommen beim GeoRIS-Online Portal', $nachricht, $header);
		
		//echo "<div style='border: 1px solid black;'><pre>$nachricht</pre></div>";
		
		}else{?>
			<form method="post" onsubmit="
				if(document.getElementById('email').value == ''){alert('Wir brauchen deien E-Mail Adresse'); return false;};
				if(document.getElementById('agb').checked != true){alert('Die AGBs müssen aczepiert werden'); return false;};">
				<center><table><tr><td>
					<?php if($_GET["registrieren"] == "pass"){ ?>
					<h2>Wenn Sie Ihr Passwort vergessen haben registrieren Sie ich einfach nochmal</h2>
					<br /><br />
					<?php }?>
					eMail Adresse: <input name=email id='email' /><br /><br />
					<div style="border: 1px solid black;">
						<center><h2>AGB</h2></center>
						Foo	Bla Bla...
					</div><br />
					<br />
					<input type="checkbox" id=agb /> Ich akzeptiere die AGB<br />
					<br />
					<input type="submit" value="Registrieren"/>
					
				</td></tr></table></center>
			</form>
	<?php 	} 
		}else{?>
		<form method="post" action="?"><table>
		<tr>
			<td>Mailadresse</td>
			<td><input name=email size="20" value="<?=$_COOKIE["email"]?>"/></td>
		</tr>
		<tr>
			<td>Passwort</td>
			<td><input type=password name=pass style="width: 100%;" /></td>
		</tr>
			<tr><td colspan="2"><br /></td>
		</tr>
		<tr>
			<td colspan="2">
				<center>
					<input type=hidden name=login value=true />
					<input type=hidden name=setcookie value=email />
					<input type=submit value="Login" style="width: 45%;"/>
					<input type=button onclick="self.location.href='?registrieren=true'" style="width: 50%;" value="Registrieren"/>
					<br />
					<input type=button onclick="self.location.href='?registrieren=pass'" style="width: 95%;" value="Password vergessen"/>
				</center>
			</td>
		</tr>
		</table></form>
	<?php }
	}else{
		
		if ($_POST["template"])		$_SESSION["user"]["default"]["template"] = $_POST["template"]; 
		
		if ($_POST["save"]){
			$_SESSION["user"]["radius"] 			= $_POST["radius"];
			$_SESSION["user"]["geolocat"]["lat"]	= $_POST["lat"];
			$_SESSION["user"]["geolocat"]["lon"]	= $_POST["lon"];
			
			
			USER_set(array(
				"mail" 		=> $_SESSION["user"]["mail"],
				"radius"	=> $_SESSION["user"]["radius"],
				"lat"		=> $_SESSION["user"]["geolocat"]["lat"],
				"lon"		=> $_SESSION["user"]["geolocat"]["lon"],
				"template"	=> $_SESSION["user"]["default"]["template"]
			)); ?>
			
		<span style="font-size: 16;">
			Einstellungen wurden Gespeichert.
		</span>
			
		<?php }else{?>
		
		<div style="width: 45%; float:left;">
			<form method="post" id=form>
			<table style="width: 99%" >
				<tr>
					<td colspan="2"><h2>Mein Wohnort</h2></td>
				</tr>
				<tr>
					<td>Längengrad</td>
					<td>
						<input name=lat id="click_lat" value="<?=($_SESSION["user"]["geolocat"]["lat"]<>52.517?$_SESSION["user"]["geolocat"]["lat"]:"")?>"/>
					</td>
				</tr>
				<tr>
					<td>Breitengrad</td>
					<td><input name=lon id="click_lon" value="<?=($_SESSION["user"]["geolocat"]["lon"]<>13.38888?$_SESSION["user"]["geolocat"]["lon"]:"")?>"/></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type=button onclick="loadScript('/js/geolocat.js')" value="Erkennen" id="geolocat_click"/>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><br /></td>
				</tr>
				<tr>
					<td>Informiere mich über Anträge im Radius von<br />
					<span style="font-size: 85%">(Diese Funktion befindet sich noch in der Testphase)</span></td>
					<td>
						<select name="radius">
						  <option <?=$_SESSION["user"]["radius"]==  0?"selected='selected'":""?> value="0">- Keine -</option>
						  <option <?=$_SESSION["user"]["radius"]==0.1?"selected='selected'":""?> value="0.1">100 m</option>
						  <option <?=$_SESSION["user"]["radius"]==0.5?"selected='selected'":""?> value="0.5">500 m</option>
						  <option <?=$_SESSION["user"]["radius"]==  1?"selected='selected'":""?> value="1">1 km</option>
						  <option <?=$_SESSION["user"]["radius"]==  2?"selected='selected'":""?> value="2">2 km</option>
						  <option <?=$_SESSION["user"]["radius"]==  4?"selected='selected'":""?> value="4">4 km</option>
						  <option <?=$_SESSION["user"]["radius"]==  6?"selected='selected'":""?> value="6">6 km</option>
						  <option <?=$_SESSION["user"]["radius"]==  8?"selected='selected'":""?> value="8">8 km</option>
						  <option <?=$_SESSION["user"]["radius"]== 10?"selected='selected'":""?> value="10">10 km</option>
						  <option <?=$_SESSION["user"]["radius"]== 15?"selected='selected'":""?> value="15">15 km</option>
						  <option <?=$_SESSION["user"]["radius"]== 30?"selected='selected'":""?> value="30">30 km</option>
						</select>
					</td>
				</tr>
			
			<?php if (($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin")){?>
			
				<tr>
					<td colspan="2"><br /><h2>Manager Einstellungen:</h2></td>
				</tr>
				<tr>
					<td>Default Template:</td>
					<td><select name='template'><?
						$set = $_templates;
				
						while( list ( $key, $val ) = each ( $set ) ){
							if ($_SESSION["user"]["default"]["template"] == $val["id"]){
								echo "<option selected='selected' value='{$val["id"]}'>{$val["name"]}</option>\n";
							}else{
								echo "<option value='{$val["id"]}'>{$val["name"]}</option>\n";
							}
						}?></select></td>
				</tr>
			
			<?php }?>
				<tr>
					<td colspan="2"><h2></h2></td>
				</tr>
				<tr>
					<td colspan="2"><center>
						<input type=submit name=save value="Speicher"/>
						<input type=button onclick="self.location.href='?logout=true'" value="Abmelden"/>
					</center></td>
				</tr>
			</table>
			</form>
		</div>	
		<div id="map" style="width: 50%; height: 90%; border: 1px solid black; float:left;" class="smallmap"></div>	
		<table><tr><td><center>
	<?php } }?>
</center></td></tr></table>