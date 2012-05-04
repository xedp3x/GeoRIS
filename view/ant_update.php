<?php
if (!(($_SESSION["user"]["group"] == "manager") OR ($_SESSION["user"]["group"] == "admin")))
	error("Access Denied","Für diese Seite reichen Ihre rechte nicht aus");

	

if ($_GET["update"]){
	$set = ANTRAG_list("template = '".$_GET["template"]."'");
	
	if (count($set) < 2){?>
	<body>
	<?=$LOGO; ?>
	<h1 id="title">Fehler</h1>
		
		<center>
			<span style="font-size: 24">
				Aus technichen Gründen funktionirt diese Aktion nur, <br />
				wenn min 2 Anträge in diesem Template vorhanden sind.
			</span>
		</center>
	</body>
		
	<?php }else{?>
	  <body onload="gruppenbefehl(Array(<?php
	  
	  
	  	$_SESSION["check"]["template"] = $_GET["template"];
	  	$_SESSION["check"]["site"]["old"] = array();
	  	$_SESSION["check"]["site"]["new"] = array();
	  	
		
		while( list ( $key, $val ) = each ( $set ) ){
			$out[] =$val["id"];
			$_SESSION["check"]["site"]["old"][$val["id"]] = $val;
		}

		echo implode(",",$out);
		?>),'api.php?modul=update&check=');"/>
		<?=$LOGO; ?>
		<h1 id="title">Bitte warten</h1>
		
	<div>
		<form id="form">
			
		</form>
	
		<div style="position: relative; width:100%; background-color: #C0C0C0; border: solid 1px #000000;">
			<span id="counter3" style="position: absolute; width: 100%; z-index: 3; text-align: center; font-weight: bold;">0%</span> 
			<div id="status3" style="position: relative; background-color: #00FF00; width:0px; height: 22px; border-right: solid 1px #000000; z-index: 2;">&thinsp;</div>
		</div>	
		<center style="margin-top: 20px;">
			<img src="/img/Searching.jpg" />
		</center>
		
	</div>
  </body>
<?php } 
	}else{?>
	<body>
		<?=$LOGO; ?>
		<h1 id="title">Updates</h1>
	<form>
		<input type=hidden name=update value=true />
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
			</select>
		<input type=submit value="Update" />
		<input type=button onclick="self.location.href='/ant_list.html'" value="Abbrechen"/>
	</form>
	<?php if (!$_SESSION["check"]){?>
		Bitte ein Template Laden.
	<?php }else{?>
		<h2>Änderungen:</h2>
		<table>
	<?php 
		
	
		$set = ANTRAG_list("template = '".$_SESSION["check"]["template"]."'");
	  	$_SESSION["check"]["site"]["old"] = array();
	  	while( list ( $key, $val ) = each ( $set ) ){
			$out[] =$val["id"];
			$_SESSION["check"]["site"]["old"][$val["id"]] = $val;
		}
	
	
		$set = $_SESSION["check"]["site"]["old"];
			
		while( list ( $key, $val ) = each ( $set ) ){
			if ($_SESSION["check"]["site"]["old"][$val["id"]]["version"] <>
				$_SESSION["check"]["site"]["new"][$val["id"]]["version"]){?>
		
		<tr>
			<td><?=$val["name"]?></td>
			<td><a href="/ant_edit.html?edit=<?=$val["ant"]?>&renew=<?=$val["id"]?>" ><?=$val["titel"]?></a></td>
		</tr>
		
		
			<?php }
		}	
	}
	?></table>
	</body>
<?php }
