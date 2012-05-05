
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

function ANTRAG_bygeo($lat, $lon ,$rad){
	return SQL_query_as_array("Select geb.ent as ent, antrag.* from antrag, (
	Select gebiet.ant, pkt.ent as ent from gebiet, (
		select gebiet_id, (
				(($lat-lat) *  ($lat-lat) * 12392) +
				(($lon-lon) *  ($lon-lon) *  4655)
			) AS ent from punkt where 
			(
				(($lat-lat) *  ($lat-lat) * 12392) +
				(($lon-lon) *  ($lon-lon) *  4655) 
			) < ".($rad*$rad)."
			Group by gebiet_id
			Order By Ent ASC
		) as pkt
		where gebiet.gebiet_id = pkt.gebiet_id
		Group by ant
	) as geb
	where geb.ant = antrag.ant
	Order by ent asc;");
}