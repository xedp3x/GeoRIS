
<?php

function ANTRAG_create($template, $id){
	global $_templates;
	
	$set = $_templates;
	while( list ( $key, $val ) = each ( $set ) ){
		if ($val["id"] == $template)
			$PL = new $val["type"]($val);
	}
	
	if (!isset($PL)){
		error("Antrag_create","Das Template '$template' konte nicht gefunden werden!");
	}
	
	return $PL->get_by_id($id);
}


function ANTRAG_save($ant, $kml){
	$tosave = array("ant","template","id","name","titel","status","art","version","text","url","date","pad","wiki","forum");
	
	$set = $ant;
	while( list ( $key, $val ) = each ( $set ) ){
		if (in_array($key,$tosave))
			$save[$key] = $val;
	}
	
	if ($save["ant"] == ''){
		unset($save["ant"]);
		$ant = SQL_insert("antrag",$save);
	}else{
		SQL_insert_update("antrag",$save);
		$ant = $save["ant"];
	}
	
	SQL_delete("gebiet","ant = $ant");
	
	$tosave = array("way","inner","outer");
	while( list ( $key1, $role ) = each ( $tosave ) ){
		$set = $kml[$role];
		while( list ( $key2, $val ) = each ( $set ) ){
			$gebiet_id = SQL_insert("gebiet",array(
				 	"role" 	=> $role,
					"ant" 	=> $ant,
				)); 
			while( list ( $seq, $node ) = each ( $val["data"] ) ){
				SQL_insert("punkt",array(
				 	"gebiet_id" => $gebiet_id,
					"sequence" 	=> $seq,
					"lat"		=> $node["lat"],
					"lon"		=> $node["lon"] 
				)); 
			}
		}
	}
	return true;	
}

function ANTRAG_delete($id){	
	return SQL_delete('antrag',"ant = $id");
}

function ANTRAG_load($id){	
	global $_SESSION;
	
	$_SESSION["ant"] = SQL_select_one('antrag',"ant = $id");
	$_SESSION["kml"] = KML_load($id);
	
}


function ANTRAG_list($filter = false){
	return SQL_select_as_array('antrag', $filter,"*","`ant` DESC");
}